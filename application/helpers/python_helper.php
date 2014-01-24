<?php defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('run_python')) {
    function run_python($script, $params, $implode_char=" ") {
        // Escape the shell parameters
        $clean_params = array();
        foreach ($params as $p) {
            if (is_array($p)) {
                foreach ($p as $np) {
                    $clean_params[] = escapeshellarg($np);
                }
            } else {
                $clean_params[] = escapeshellarg($p);
            }
        }
        $str_params = implode(' ', $clean_params);

        $cmd = PYTHON . " " . PYTHON_SCRIPTS . "$script $str_params 2>&1";

        $data = "";
        $handle = popen($cmd, 'r');
        while (!feof($handle)) {
            $data .= fgets($handle);
        }
        fclose($handle);
        // return implode($implode_char, $data);
        return $data;
    }
}