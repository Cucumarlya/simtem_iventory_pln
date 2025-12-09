<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    |
    | Set some default values. It is possible to add all defines that can be set
    | in dompdf_config.inc.php. You can also override the entire config file.
    |
    */
    'show_warnings' => false,   // Throw an Exception on warnings from dompdf

    'public_path' => null,  // Override the public path if needed

    /*
     * Dejavu Sans font is missing glyphs for converted entities, turn it off if you need to show € and £.
     */
    'convert_entities' => true,

    'options' => [
        /**
         * The location of the DOMPDF font directory
         *
         * The location of the directory where DOMPDF will store fonts and font metrics
         * Note: This directory must exist and be writable by the webserver process.
         * *Please note the trailing slash.*
         *
         * Notes regarding fonts:
         * Additional .afm font metrics can be added by executing load_font.php from command line.
         *
         * Only the original "Base 14 fonts" are present on all pdf viewers. Additional fonts must
         * be embedded in the pdf file or the PDF may not display correctly. This can significantly
         * increase file size unless font subsetting is enabled. Before embedding a font please
         * review your rights under the font license.
         *
         * Any font specification in the source HTML is translated to the closest font available
         * in the font directory.
         *
         * The pdf standard "Base 14 fonts" are:
         * Courier, Courier-Bold, Courier-Oblique, Courier-BoldOblique,
         * Helvetica, Helvetica-Bold, Helvetica-Oblique, Helvetica-BoldOblique,
         * Times-Roman, Times-Bold, Times-Italic, Times-BoldItalic,
         * Symbol, ZapfDingbats.
         */
        'font_dir' => storage_path('fonts/'), // advised by dompdf (https://github.com/dompdf/dompdf/pull/782)

        /**
         * The location of the DOMPDF font cache directory
         *
         * This directory contains the cached font metrics for the fonts used by DOMPDF.
         * This directory can be the same as DOMPDF_FONT_DIR
         *
         * Note: This directory must exist and be writable by the webserver process.
         */
        'font_cache' => storage_path('fonts/'),

        /**
         * The location of a temporary directory.
         *
         * The directory specified must be writeable by the webserver process.
         * The temporary directory is required to download remote images and when
         * using the PFDLib back end.
         */
        'temp_dir' => sys_get_temp_dir(),

        /**
         * ==== IMPORTANT ====
         *
         * dompdf's "chroot": Prevents dompdf from accessing system files or other
         * files on the webserver.  All local files opened by dompdf must be in a
         * subdirectory of this directory.  DO NOT set it to '/' since this could
         * allow an attacker to use dompdf to read any files on the server.  This
         * should be an absolute path.
         * This is only checked on command line call by dompdf.php, but not by
         * direct class use like:
         * $dompdf = new DOMPDF();  $dompdf->load_html($htmldata); $dompdf->render(); $pdf = $dompdf->output();
         */
        'chroot' => realpath(base_path()),

        /**
         * Protocol whitelist
         *
         * Protocols and PHP wrappers allowed in URIs, and the validation rules
         * that determine if a resouce may be loaded. Full support is not guaranteed
         * for the protocols/wrappers specified
         * by this array.
         *
         * @var array
         */
        'allowed_protocols' => [
            'file://' => ['rules' => []],
            'http://' => ['rules' => []],
            'https://' => ['rules' => []],
        ],

        /**
         * Operational artifact (log files, temporary files) path validation
         */
        'artifactPathValidation' => null,

        /**
         * @var string
         */
        'log_output_file' => null,

        /**
         * Whether to enable font subsetting or not.
         */
        'enable_font_subsetting' => false,

        /**
         * The PDF rendering backend to use
         *
         * Valid settings are 'PDFLib', 'CPDF' (the bundled R&OS PDF class), 'GD' and 'auto'. 'auto' will
         * look for PDFLib and use it if found, or if not it will fall back on CPDF. 'GD' renders PDFs to graphic
         * files. {@link * Canvas_Factory} ultimately determines which rendering class to instantiate
         * based on this setting.
         *
         * Both PDFLib & CPDF rendering backends provide sufficient rendering performance for dompdf, on average 0.1
         * seconds faster than GD with all fonts embedded.  CPDF is about 0.03 seconds slower than PDFLib.  GD
         * rendering is about 0.5 seconds slower than PDFLib.  PDFLib is about 3.7 times faster than GD when
         * all fonts are embedded.
         *
         * PDFLib is a commercial product.  Please see {@link http://www.pdflib.com} for more information.
         *
         * CPDF is the default rendering backend.
         */
        'pdf_backend' => 'CPDF',

        /**
         * html target media view which should be rendered into pdf.
         * List of types and parsing rules for future extensions:
         * http://www.w3.org/TR/REC-html40/types.html
         *   screen, tty, tv, projection, handheld, print, braille, aural, all
         * Note: aural is deprecated in CSS 2.1 because it is replaced by speech in CSS 3.
         * Note, even though the generated pdf file is intended for print output,
         * the desired content might be different (e.g. screen or projection view of html file).
         * Therefore allow specification of content here.
         */
        'default_media_type' => 'screen',

        /**
         * The default paper size.
         *
         * North America standard is "letter"; other countries generally "a4"
         *
         * @see Dompdf\Adapter\CPDF::PAPER_SIZES for valid sizes ('letter', 'legal', 'A4', etc.)
         */
        'default_paper_size' => 'a4',

        /**
         * The default paper orientation.
         *
         * The orientation of the page (portrait or landscape).
         *
         * @var string
         */
        'default_font' => 'serif',

        /**
         * The default paper orientation.
         *
         * The orientation of the page (portrait or landscape).
         *
         * @var string
         */
        'dpi' => 96,

        /**
         * The default paper orientation.
         *
         * The orientation of the page (portrait or landscape).
         *
         * @var string
         */
        'enable_php' => false,

        /**
         * Enable embedded PHP
         *
         * If this setting is set to true then DOMPDF will automatically evaluate
         * embedded PHP contained within <script type="text/php"> ... </script> tags.
         *
         * ==== IMPORTANT ====
         * Enabling this for documents you do not trust (e.g. arbitrary remote html pages) is a security risk.
         * Embedded scripts are run with the same level of system access available to dompdf.
         * Set this option to false (recommended) if you wish to process untrusted documents.
         *
         * This setting may increase the risk of system exploit. Do not change
         * this settings without understanding the consequences. Additional
         * documentation is available on the dompdf wiki at:
         * https://github.com/dompdf/dompdf/wiki
         *
         * @var bool
         */
        'enable_javascript' => true,

        /**
         * Enable inline JavaScript
         *
         * If this setting is set to true then DOMPDF will automatically insert
         * JavaScript code contained within <script type="text/javascript"> ... </script> tags.
         *
         * @var bool
         */
        'enable_remote' => true,

        /**
         * Enable remote file access
         *
         * If this setting is set to true, DOMPDF will access remote sites for
         * images and CSS files as required.
         *
         * ==== IMPORTANT ====
         * This can be a security risk, in particular in combination with isPhpEnabled and
         * allowing remote html code to be passed to $dompdf = new DOMPDF(); $dompdf->load_html(...);
         * This allows anonymous users to download legally doubtful internet content which on
         * tracing back appears to being downloaded by your server, or allows malicious php code
         * in remote html pages to be executed by your server with your account privileges.
         *
         * This setting may increase the risk of system exploit. Do not change
         * this settings without understanding the consequences. Additional
         * documentation is available on the dompdf wiki at:
         * https://github.com/dompdf/dompdf/wiki
         *
         * @var bool
         */
        'font_height_ratio' => 1.1,

        /**
         * Use the HTML5 Lib parser
         *
         * @deprecated This feature is now always on in dompdf 2.x
         * @var bool
         */
        'enable_html5_parser' => true,
    ],

];