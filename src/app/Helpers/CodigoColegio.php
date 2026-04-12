<?php
namespace App\Helpers;

class CodigoColegio
{
    public static function generar($nombreColegio, $beneficiarioId)
    {
        $stopWords = ['de', 'del', 'la', 'las', 'los', 'y', 'en'];

        $palabras = preg_split('/\s+/', strtolower($nombreColegio));

        $validas = [];

        foreach ($palabras as $palabra) {

            // ignorar stopwords
            if (in_array($palabra, $stopWords)) {
                continue;
            }

            // ignorar números
            if (is_numeric($palabra)) {
                continue;
            }

            // ignorar números romanos
            if (preg_match('/^(i|ii|iii|iv|v|vi|vii|viii|ix|x)$/i', $palabra)) {
                continue;
            }

            $validas[] = $palabra;
        }

        if (count($validas) === 0) {
            $sigla = 'XX';
        } elseif (count($validas) === 1) {
            $sigla = strtoupper(substr($validas[0], 0, 2));
        } else {
            $sigla = '';
            foreach ($validas as $palabra) {
                $sigla .= strtoupper(substr($palabra, 0, 1));
            }
        }

        $numero = str_pad($beneficiarioId, 5, '0', STR_PAD_LEFT);

        return $sigla . '-' . $numero;
    }
}
