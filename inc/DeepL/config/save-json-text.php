<?php

/* función que guarda el contenido en un JSON  */
// Cargar autoload de Voku solo si existe
function cargar_voku_autoload() {
    $autoload = get_template_directory() . '/vendor/autoload.php';
    if (file_exists($autoload)) {
        require_once $autoload;
    } else {
        error_log('No se encontró autoload.php');
    }
}
add_action('init', 'cargar_voku_autoload');

// Usamos el namespace después de que WordPress esté listo
add_action('init', function() {
    if (class_exists('\voku\helper\HtmlDomParser')) {
        add_action('save_post_post', 'generar_json_nota', 10, 3);
    }
});

use voku\helper\HtmlDomParser;

function generar_json_nota($post_ID, $post, $update) {
    if ($post->post_status !== 'publish') return;

    sleep(1);

    $url = get_permalink($post_ID);
    $ID = get_post_type($post_ID) . '-' . $post_ID;
    $response = wp_remote_get($url);

    if (is_wp_error($response)) {
        error_log('Error al obtener HTML del post: ' . $response->get_error_message());
        return;
    }

    $html = wp_remote_retrieve_body($response);
    if (empty($html)) {
        error_log('El HTML recuperado está vacío');
        return;
    }

    $dom = HtmlDomParser::str_get_html($html);
    if (!$dom) {
        error_log('No se pudo parsear el HTML');
        return;
    }

    $titulo = $dom->findOne('.titulo-nota-es')?->text ?? '';
    $fecha = $dom->findOne('.date-nota')?->text ?? '';

    $contenidoOrdenado = procesar_bloques_contenido($dom->findMulti('.contenido-nota-es')->getIterator());

    $tituloEn = $dom->findOne('.titulo-nota-en')?->text ?? '';
    $contenidoEn = $dom->findMulti('.contenido-nota-en')->getIterator();
    $contenidoOrdenadoEn = procesar_bloques_contenido($contenidoEn);

    $dirname = get_template_directory() . '/inc/DeepL/notas/';
    if (!file_exists($dirname)) mkdir($dirname, 0755, true);

    $file = $dirname . 'notas-reforestamos.json';

    $existingData = [];
    if (file_exists($file)) {
        $jsonString = file_get_contents($file);
        $existingData = json_decode($jsonString, true);
        if (!is_array($existingData)) $existingData = [];
    }

    $existingData[$ID]['notaES'] = [
        'title'     => $titulo,
        'url'       => $url,
        'date'      => $fecha,
        'contenido' => $contenidoOrdenado
    ];

    if (!empty($tituloEn) && !empty($contenidoOrdenadoEn)) {
        $existingData[$ID]['notaEn'] = [
            'title' => $tituloEn,
            'url' => $url,
            'date' => $fecha,
            'contenido' => $contenidoOrdenadoEn
        ];
    } else {
        traslate_content_es($existingData, $ID, $file);
    }

    file_put_contents($file, json_encode($existingData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function procesar_bloques_contenido($contenedores) {
    $contenido = [];

    foreach ($contenedores as $contenedor) {
        foreach ($contenedor->children() as $element) {
            $tag = strtolower($element->tag);
            $class = $element->getAttribute('class') ?? '';

            if ($tag === 'div' && strpos($class, 'd-flex') !== false && strpos($class, 'justify-content-center') !== false) {
                $img = $element->findOne('img');
                if ($img) {
                    $src = $img->getAttribute('src');
                    if ($src) {
                        $contenido[] = [
                            'type' => 'img.centered',
                            'src' => $src,
                            'alt' => $img->getAttribute('alt') ?? '',
                            'html' => $element->outerhtml
                        ];
                        continue;
                    }
                }
            }

            if (preg_match('/\bbwg/', $class)) {
                $contenido[] = [
                    'type' => 'plugin-galeria',
                    'html' => $element->outerhtml
                ];
                continue;
            }

            if (in_array($tag, ['h1', 'h2', 'h3', 'p', 'span', 'div'])) {
                $texto = trim($element->text);
                $html = $element->innerhtml;

                $aTags = $element->findMulti('a');
                if (!empty($aTags)) {
                    foreach ($aTags as $a) {
                        $html = str_replace($a->text, $a->outerhtml, $html);
                    }
                }

                $bloque = [
                    'type' => $tag,
                    'text' => $texto,
                    'html' => "<{$tag}>$html</{$tag}>"
                ];

                if (!empty($aTags)) {
                    $bloque['links'] = [];
                    foreach ($aTags as $a) {
                        $bloque['links'][] = [
                            'href' => $a->getAttribute('href'),
                            'text' => $a->text,
                            'html' => $a->outerhtml
                        ];
                    }
                }

                $imgTags = $element->findMulti('img');
                if (!empty($imgTags)) {
                    $bloque['images'] = [];
                    foreach ($imgTags as $img) {
                        $src = $img->getAttribute('src');
                        if ($src) {
                            $bloque['images'][] = [
                                'src' => $src,
                                'alt' => $img->getAttribute('alt') ?? '',
                                'html' => $img->outerhtml
                            ];
                        }
                    }
                }

                $iframes = $element->findMulti('iframe');
                if (!empty($iframes)) {
                    $bloque['iframes'] = [];
                    foreach ($iframes as $iframe) {
                        $bloque['iframes'][] = [
                            'src' => $iframe->getAttribute('src'),
                            'html' => $iframe->outerhtml
                        ];
                    }
                }

                $contenido[] = $bloque;
            } elseif ($tag === 'img') {
                $src = $element->getAttribute('src');
                if ($src) {
                    $contenido[] = [
                        'type' => 'img',
                        'src' => $src
                    ];
                }
            } elseif ($tag === 'figure') {
                $contenido[] = [
                    'type' => 'figure',
                    'html' => $element->outerhtml
                ];
            } elseif (in_array($tag, ['script', 'style']) && preg_match('/bwg/', $class . $element->innerhtml)) {
                $contenido[] = [
                    'type' => 'plugin-galeria',
                    'html' => $element->outerhtml
                ];
            } elseif (in_array($tag, ['script', 'style'])) {
                $contenido[] = [
                    'type' => $tag,
                    'html' => $element->innerhtml
                ];
            } elseif ($tag === 'iframe') {
                $parent = $element->parentNode();
                if ($parent) {
                    $contenido[] = [
                        'type' => 'iframe',
                        'html' => $parent->innerhtml
                    ];
                }
            }
        }
    }

    return $contenido;
}

function traslate_content_es(&$data, $ID, $file) {
    $authKey = get_option('clave_api_deepl', '');
    $language = 'en-US';

    if (!$authKey) {
        error_log('La clave API no ha sido configurada.');
        return;
    }

    require_once get_template_directory() . '/vendor/autoload.php';
    $translator = new \DeepL\Translator($authKey);

    if (!isset($data[$ID]['notaES'])) {
        error_log('No se encontró el contenido para traducir.');
        return;
    }

    $notaOriginal = $data[$ID]['notaES'];
    $contenidoTraducido = [];

    $noTraducibles = ['img', 'style', 'script', 'div.bwg_container', 'plugin-galeria', 'iframe', 'figure'];

    foreach ($notaOriginal['contenido'] as $bloque) {
        if (in_array($bloque['type'], $noTraducibles)) {
            $contenidoTraducido[] = $bloque;
            continue;
        }

        if (!empty($bloque['text'])) {
            try {
                $translatedText = $translator->translateText($bloque['text'], null, $language)->text;
            } catch (Exception $e) {
                error_log('Error al traducir texto: ' . $e->getMessage());
                $translatedText = $bloque['text'];
            }

            $translatedHtml = "<{$bloque['type']}>$translatedText</{$bloque['type']}>";

            if (isset($bloque['links'])) {
                foreach ($bloque['links'] as $link) {
                    $translatedHtml = str_replace($link['text'], $link['html'], $translatedHtml);
                }
            }

            $translatedBlock = [
                'type' => $bloque['type'],
                'text' => $translatedText,
                'html' => $translatedHtml
            ];

            if (isset($bloque['links'])) {
                $translatedBlock['links'] = $bloque['links'];
            }

            $contenidoTraducido[] = $translatedBlock;
        } else {
            $contenidoTraducido[] = $bloque;
        }
    }

    $data[$ID]['notaEn'] = [
        'title' => $translator->translateText($notaOriginal['title'], null, $language)->text,
        'url' => $notaOriginal['url'],
        'date' => $notaOriginal['date'],
        'contenido' => $contenidoTraducido
    ];

    $post_id = str_replace('post-', '', $ID);
    update_post_meta($ID, 'nota_blog_ingles_titulo_nota_ingles', $translatedTitle);
    update_post_meta($ID, 'nota_blog_ingles_contenido_nota_ingles', wp_json_encode($contenidoTraducido));

    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}
