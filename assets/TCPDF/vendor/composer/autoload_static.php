<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit9878bffdfd65754f69eeaeb477733c15
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'FontLib\\' => 8,
        ),
        'D' => 
        array (
            'Dompdf\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'FontLib\\' => 
        array (
            0 => __DIR__ . '/..' . '/phenx/php-font-lib/src/FontLib',
        ),
        'Dompdf\\' => 
        array (
            0 => __DIR__ . '/..' . '/dompdf/dompdf/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'T' => 
        array (
            'TCPDF' => 
            array (
                0 => __DIR__ . '/..' . '/laurentbrieu/tcpdf/src',
            ),
        ),
        'S' => 
        array (
            'Svg\\' => 
            array (
                0 => __DIR__ . '/..' . '/phenx/php-svg-lib/src',
            ),
            'Sabberworm\\CSS' => 
            array (
                0 => __DIR__ . '/..' . '/sabberworm/php-css-parser/lib',
            ),
        ),
    );

    public static $classMap = array (
        'Cpdf' => __DIR__ . '/..' . '/dompdf/dompdf/lib/Cpdf.php',
        'Datamatrix' => __DIR__ . '/../..' . '/include/barcodes/datamatrix.php',
        'HTML5_Data' => __DIR__ . '/..' . '/dompdf/dompdf/lib/html5lib/Data.php',
        'HTML5_InputStream' => __DIR__ . '/..' . '/dompdf/dompdf/lib/html5lib/InputStream.php',
        'HTML5_Parser' => __DIR__ . '/..' . '/dompdf/dompdf/lib/html5lib/Parser.php',
        'HTML5_Tokenizer' => __DIR__ . '/..' . '/dompdf/dompdf/lib/html5lib/Tokenizer.php',
        'HTML5_TreeBuilder' => __DIR__ . '/..' . '/dompdf/dompdf/lib/html5lib/TreeBuilder.php',
        'PDF417' => __DIR__ . '/../..' . '/include/barcodes/pdf417.php',
        'QRcode' => __DIR__ . '/../..' . '/include/barcodes/qrcode.php',
        'TCPDF' => __DIR__ . '/../..' . '/tcpdf.php',
        'TCPDF2DBarcode' => __DIR__ . '/../..' . '/tcpdf_barcodes_2d.php',
        'TCPDFBarcode' => __DIR__ . '/../..' . '/tcpdf_barcodes_1d.php',
        'TCPDF_COLORS' => __DIR__ . '/../..' . '/include/tcpdf_colors.php',
        'TCPDF_FILTERS' => __DIR__ . '/../..' . '/include/tcpdf_filters.php',
        'TCPDF_FONTS' => __DIR__ . '/../..' . '/include/tcpdf_fonts.php',
        'TCPDF_FONT_DATA' => __DIR__ . '/../..' . '/include/tcpdf_font_data.php',
        'TCPDF_IMAGES' => __DIR__ . '/../..' . '/include/tcpdf_images.php',
        'TCPDF_IMPORT' => __DIR__ . '/../..' . '/tcpdf_import.php',
        'TCPDF_PARSER' => __DIR__ . '/../..' . '/tcpdf_parser.php',
        'TCPDF_STATIC' => __DIR__ . '/../..' . '/include/tcpdf_static.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit9878bffdfd65754f69eeaeb477733c15::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit9878bffdfd65754f69eeaeb477733c15::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit9878bffdfd65754f69eeaeb477733c15::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit9878bffdfd65754f69eeaeb477733c15::$classMap;

        }, null, ClassLoader::class);
    }
}
