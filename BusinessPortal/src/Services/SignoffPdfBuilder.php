<?php

/**
 * SignoffPdfBuilder — renders the "Installation Completion Sign Off" PDF via Dompdf.
 * No business logic beyond layout — data is passed in fully prepared.
 */
class SignoffPdfBuilder
{
    /**
     * @param array $business  ['name','address','city','state','zip','phone','email']
     * @param array $order     ['id']
     * @param array $signoff   ['customer_name','customer_address','worked_area','checklist','signature_text','signed_at']
     * @return string raw PDF bytes
     */
    public static function build(array $business, array $order, array $signoff): string
    {
        $config = require __DIR__ . '/../../config/pdf.php';
        require_once $config['dompdf_autoload'];

        ob_start();
        // Vars consumed by the template: $business, $order, $signoff
        require __DIR__ . '/templates/signoff-document.php';
        $html = ob_get_clean();

        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', false);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'Lato');
        $options->setTempDir($config['tmp_dir']);
        $options->setFontDir($config['font_cache_dir']);
        $options->setFontCache($config['font_cache_dir']);
        $options->setChroot(realpath(__DIR__ . '/../..'));

        $dompdf = new \Dompdf\Dompdf($options);

        $fontMetrics = $dompdf->getFontMetrics();
        $fontsDir = 'file://' . $config['fonts_dir'];
        $fontMetrics->registerFont(['family' => 'Lato', 'weight' => 'normal', 'style' => 'normal'], $fontsDir . '/Lato-Regular.ttf');
        $fontMetrics->registerFont(['family' => 'Lato', 'weight' => 'bold', 'style' => 'normal'], $fontsDir . '/Lato-Bold.ttf');
        $fontMetrics->registerFont(['family' => 'Lato', 'weight' => 'normal', 'style' => 'italic'], $fontsDir . '/Lato-Italic.ttf');
        $fontMetrics->registerFont(['family' => 'Lato', 'weight' => 'bold', 'style' => 'italic'], $fontsDir . '/Lato-BoldItalic.ttf');

        $dompdf->setPaper('A4', 'portrait');
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->render();

        return $dompdf->output();
    }
}
