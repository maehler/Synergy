<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
Model for getting JSON networks that match the cytoscape.js specification.
Will not include co-regulation.

NOTE: In the `corr` table, gene1_id is ALWAYS larger than gene2_id!
*/
class Network_model extends CI_Model {

	function get_network($genes, $ntype='clr_complete', $th=5, $expand=FALSE, $json=TRUE) {
		/**
		 * Get a gene network for the given genes and network type.
		 *
		 * @return array|string Returns a cytoscape.js compatible network representation either as JSON or as an array
		 * @param array $genes The array of gene ids to build the network for
		 * @param string $ntype The column in the `corr` table to use
		 * @param float $th Correlation threshold, only use correlations above this
		 * @param bool $expand If TRUE, expand the network to include the first neighbors of each gene
		 * @param bool $json If TRUE, return a JSON representation of the network, otherwise return an array
		 */
		$network = array(
			'nodes' => array(),
			'edges' => array()
		);
		if (!$genes) {
			if ($json) {
				return json_encode($network);
			} else {
				return $network;
			}
		}
		// Find all neighboring genes of the input genes if expansion is wanted
		if ($expand) {
			$this->db->select('g1.id as id1, g1.orf_id as orf1, g1.tf as tf1, g2.id as id2, g2.orf_id as orf2, g2.tf as tf2')
				->from('corr as c')
				->join('gene AS g1', 'g1.id = c.gene1_id', 'left')
				->join('gene AS g2', 'g2.id = c.gene2_id', 'left')
				->where('(gene1_id IN (\'' . implode($genes, '\',\'') . 
					'\') OR gene2_id IN (\'' . implode($genes, '\',\'').'\'))')
				->where("$ntype >", $th)
				->where("$ntype IS NOT NULL");
			$gene_query = $this->db->get();
			$gene_results = $gene_query->result_array();
			$nodes = array();
			foreach ($gene_results as $edge) {
				if (!array_key_exists($edge['id1'], $nodes)) {
					$nodes[$edge['id1']] = array(
						$edge['orf1'],
						$edge['tf1'] ? TRUE : FALSE,
						in_array($edge['id1'], $genes)
					);
				}
				if (!array_key_exists($edge['id2'], $nodes)) {
					$nodes[$edge['id2']] = array(
						$edge['orf2'],
						$edge['tf2'] ? TRUE : FALSE,
						in_array($edge['id2'], $genes)
					);
				}
			}
			// Make sure that genes that might be disconnected also are included
			$this->db->select('id, orf_id, tf, symbol')
				->from('gene')
				->where_in('id', $genes);
			$basket_query = $this->db->get();
			$basket_results = $basket_query->result_array();
			foreach ($basket_results as $gene) {
				if (!array_key_exists($gene['id'], $nodes)) {
					$nodes[$gene['id']] = array(
						$gene['orf_id'],
						$gene['tf'] ? TRUE : FALSE,
						TRUE,
						$gene['symbol']
					);
				}
			}
		} else {
			// Select genes
			$this->db->select('id, orf_id, tf, symbol')
				->from('gene')
				->where_in('id', $genes);
			$gene_query = $this->db->get();
			$gene_result = $gene_query->result_array();
			$nodes = array();
			foreach ($gene_result as $gene) {
				$nodes[$gene['id']] = array(
					$gene['orf_id'],
					$gene['tf'] ? TRUE : FALSE,
					TRUE,
					$gene['symbol']
				);
			}
		}

		// Select edges among the genes
		$this->db->select("gene1_id, g1.orf_id as orf1, g1.tf as tf1, gene2_id, g2.orf_id as orf2, g2.tf as tf2, $ntype")
			->from('corr as c')
			->join('gene as g1', 'g1.id = c.gene1_id', 'left')
			->join('gene as g2', 'g2.id = c.gene2_id', 'left')
			->where_in('gene1_id', array_keys($nodes))
			->where_in('gene2_id', array_keys($nodes))
			->where("$ntype >", $th)
			->where("$ntype IS NOT NULL");
		$query = $this->db->get();
		$result = $query->result_array();

		// Format the nodes
		foreach ($nodes as $nid => $vals) {
			$classes = array();
			$classes[] = $vals[1] ? 'tf' : '';
			$classes[] = $vals[2] ? 'basket' : '';
			$network['nodes'][] = array(
				'data' => array(
					'id' => strval($nid),
					'orf' => $vals[0],
					'symbol' => $vals[3]
				),
				'classes' => implode(' ', $classes)
			);
		}
		// Format the edges
		foreach ($result as $edge) {
			$network['edges'][] = array(
				'data' => array(
					'source' => strval($edge['gene1_id']),
					'target' => strval($edge['gene2_id']),
					'weight' => floatval($edge[$ntype])
				)
			);
		}

		if ($json) {
			return json_encode($network);
		} else {
			return $network;
		}
	}

	function get_neighbors($orf, $th, $ntype) {
		/**
		 * Given a gene, get its network neighbors given a threshold and a 
		 * network type.
		 * @return array A nested array of nodes formatted for cytoscape.js
		 * @param string $orf The ORF id of the gene to get neighbors for
		 * @param float $th Correlation threshold
		 * @param string $ntype Column in the `corr` table to use
		 */
		$this->db->select("g1.id AS id1, g1.orf_id AS orf1, g1.tf AS tf1, g1.symbol AS sym1, g2.id AS id2, g2.orf_id AS orf2, g2.tf AS tf2, g2.symbol AS sym2")
			->from('corr AS c')
			->join('gene AS g1', 'g1.id = c.gene1_id', 'left')
			->join('gene AS g2', 'g2.id = c.gene2_id', 'left')
			->where("(g1.orf_id = '$orf' OR g2.orf_id = '$orf')")
			->where($ntype . " >", $th)
			->where($ntype . " IS NOT NULL");
		$query = $this->db->get();
		$result = $query->result_array();

		$nodes = array();

		// Format the nodes
		foreach ($result as $edge) {
			$nodes[$edge['id1']] = array(
				'data' => array(
					'id' => $edge['id1'],
					'orf' => $edge['orf1'],
					'symbol' => $edge['sym1']
				),
				'classes' => $edge['tf1'] ? "tf" : ""
			);
			$nodes[$edge['id2']] = array(
				'data' => array(
					'id' => $edge['id2'],
					'orf' => $edge['orf2'],
					'symbol' => $edge['sym2']
				),
				'classes' => $edge['tf2'] ? "tf" : ""
			);
		}

		return array('nodes' => array_values($nodes));
	}

	function get_edges($genes, $th, $ntype) {
		/**
		 * Get all edges among a set of genes compatible with cytoscape.js
		 * @return array A nested array of edges compatible with cytoscape.js
		 * @param array $genes An array of gene ids
		 * @param float $th Correlation threshold
		 * @param string $ntype Column in the `corr` table to use
		 */
		$this->db->select("g1.id AS id1, g1.orf_id AS orf1, g2.id AS id2, g2.orf_id AS orf2, $ntype")
			->from('corr AS c')
			->join('gene AS g1', 'g1.id = c.gene1_id', 'left')
			->join('gene AS g2', 'g2.id = c.gene2_id', 'left')
			->where_in('g1.id', $genes)
			->where_in('g2.id', $genes)
			->where("$ntype >", floatval($th))
			->where("$ntype IS NOT NULL");
		$query = $this->db->get();
		$res = $query->result_array();

		$edges = array();

		// Format the edges
		foreach ($res as $edge) {
			$edges[] = array(
				'data' => array(
					'source' => strval($edge['id1']),
					'target' => strval($edge['id2']),
					'weight' => floatval($edge[$ntype])
				)
			);
		}

		return array('edges' => $edges);
	}
}
