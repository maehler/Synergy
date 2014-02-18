<?php defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('run_python')) {
    function run_python($script, $params, $implode_char=" ",
            $bg=FALSE) {
        // Escape the shell parameters
        $clean_params = array();
        foreach ($params as $p) {
            if ($p === NULL) {
                continue;
            }
            if (empty($p)) {
                continue;
            }
            if (is_array($p)) {
                foreach ($p as $np) {
                    $clean_params[] = escapeshellarg($np);
                }
            } else {
                $clean_params[] = escapeshellarg($p);
            }
        }
        $str_params = implode(' ', $clean_params);

        $cmd = PYTHON . " " . PYTHON_SCRIPTS . "$script $str_params";

        if ($bg === FALSE) {
            $cmd .= ' 2>&1';
            $data = "";
            $handle = popen($cmd, 'r');
            while (!feof($handle)) {
                $data .= fgets($handle);
            }
            fclose($handle);
        } else {
            $cmd .= ' > ' . $bg . '/output.txt 2> ' . $bg . '/log.err &';
            exec($cmd);
            $data = $bg;
        }
        
        return $data;
    }
}

if (!function_exists('run_python_stdinpipe')) {
    function run_python_stdinpipe($script, $input, $params=array(), $implode_char=" ") {
        $clean_params = array();
        foreach ($params as $p) {
            if ($p === NULL) {
                continue;
            }
            if (empty($p)) {
                continue;
            }
            if (is_array($p)) {
                foreach ($p as $np) {
                    $clean_params[] = escapeshellarg($np);
                }
            } else {
                $clean_params[] = escapeshellarg($p);
            }
        }
        $str_params = implode($implode_char, $clean_params);

        $cmd = PYTHON . ' ' . PYTHON_SCRIPTS . $script . ' ' . $str_params;

        $descriptorspec = array(
            0 => array('pipe', 'r'),
            1 => array('pipe', 'w'),
            2 => array('pipe', 'w')
        );

        $process = proc_open($cmd, $descriptorspec, $pipes);

        $output = "";

        if (is_resource($process)) {
            fwrite($pipes[0], $input);
            fclose($pipes[0]);

            $output = stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            echo stream_get_contents($pipes[2]);
            fclose($pipes[2]);

            $return_value = proc_close($process);
        }

        return $output;
    }
}
