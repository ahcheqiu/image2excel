# image2excel
Convert image to pixel-style excel file
图片转换成像素风并用excel表现

### 使用限制
1. 现在生成的excel文件需要手动调整列宽，无法把excel单元格设置为高宽相等
2. 原图片每个像素都会被转化成一个单元格，当图片很大时建议设置ConverterOption里面的最大宽高，默认都是120px(毕竟像素风都是模糊的)
3. 转化时会按照原图比例转化，已保证不超出设置的最大宽高

### Usage 使用方式
代码使用方式，请参考``demo/test.php``

看效果请运行``php demo/test.php --help`` 

添加到自己的项目中
```JSON
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/ahcheqiu/image2excel"
        }
    ],
    "require": {
        "orzorc/image2excel": "*"
    }
}
```
