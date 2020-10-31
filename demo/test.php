<?php

use Orzorc\Image2Excel\Converter;
use Orzorc\Image2Excel\ConverterOption;
use Orzorc\Image2Excel\InputImage;
use Orzorc\Image2Excel\OutputExcel;

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$parser = new Console_CommandLine(array(
    'description' => '把图片转成Excel像素画.',
    'version'     => '1.0.0'
));

$parser->addOption(
    'file',
    [
        'short_name'  => '-f',
        'long_name'   => '--file',
        'description' => '图片文件路径',
        'action'      => 'StoreString'
    ]
);
$parser->addOption(
    'maxWidth',
    [
        'short_name'  => '-w',
        'long_name'   => '--maxWidth',
        'description' => '图片压缩后的最大宽度(px)，默认120，压缩的宽和高必须同时设置才有效',
        'action'      => 'StoreInt'
    ]
);
$parser->addOption(
    'maxHeight',
    [
        'short_name'  => '-t',
        'long_name'   => '--maxHeight',
        'description' => '图片压缩后的最大高度(px)，默认120，压缩的宽和高必须同时设置才有效',
        'action'      => 'StoreInt'
    ]
);
$parser->addOption(
    'output',
    [
        'short_name'  => '-o',
        'long_name'   => '--output',
        'description' => '输出的Excel路径',
        'action'      => 'StoreString'
    ]
);


try {
    $result = $parser->parse()->options;
    $picFile = $result['file'];
    if(empty($picFile) || !file_exists($picFile) || is_dir($picFile)) {
        throw new Exception('file 必须是一个正常的文件');
    }
    $img = new InputImage($result['file']);
    $maxWidth = $result['maxWidth'] ?: 0;
    $maxHeight = $result['maxHeight'] ?: 0;
    $option = new ConverterOption();
    if($maxHeight > 0 && $maxWidth > 0) {
        $option->setReSample(true)
            ->setMaxHeight($maxHeight)
            ->setMaxWidth($maxWidth);
    }
    $outputPath = $result['output'];
    if(empty($outputPath)) {
        $outputPath = sys_get_temp_dir();
    }
    $outputPath .= DIRECTORY_SEPARATOR . 'output' . uniqid() . '.xlsx';
    $excel = new OutputExcel($outputPath);
    $convert = new Converter();
    $convert->setOptions($option)
        ->setImage($img)
        ->setExcel($excel)
        ->run();
    $excel->close();
    echo '文件已生成在' . $outputPath . "\r\n";
} catch (Exception $exc) {
    $parser->displayError($exc->getMessage());
}
