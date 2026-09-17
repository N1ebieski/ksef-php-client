# Upgrading from 1.x to 2.x

## PHP 8.4

The minimum supported version is now PHP 8.4. Support for 8.1, 8.2 and 8.3 is dropped.

## QR code generation

`endroid/qr-code` is no longer a dependency of this package. If you generate QR codes, install it yourself and pass a generator instead of a builder.

```bash
composer require endroid/qr-code:^6.1
```

The supported version is now `6.x`, which replaced the fluent builder with named arguments, so the builder configuration changes too.

**Before**

```php
use Endroid\QrCode\Builder\Builder as QrCodeBuilder;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\RoundBlockSizeMode;

$generateQRCodesHandler = new GenerateQRCodesHandler(
    qrCodeBuilder: (new QrCodeBuilder())
        ->roundBlockSizeMode(RoundBlockSizeMode::Enlarge)
        ->labelFont(new OpenSans(size: 12)),
    convertEcdsaDerToRawHandler: new ConvertEcdsaDerToRawHandler()
);
```

**After**

```php
use Endroid\QrCode\Builder\Builder as QrCodeBuilder;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\RoundBlockSizeMode;
use N1ebieski\KSEFClient\Actions\GenerateQRCodes\Generators\EndroidV6QRCodeGenerator;

$generateQRCodesHandler = new GenerateQRCodesHandler(
    qrCodeGenerator: new EndroidV6QRCodeGenerator(new QrCodeBuilder(
        roundBlockSizeMode: RoundBlockSizeMode::Enlarge,
        labelFont: new OpenSans(size: 12)
    )),
    convertEcdsaDerToRawHandler: new ConvertEcdsaDerToRawHandler()
);
```

Nothing else about `GenerateQRCodesHandler::handle()` changes.

### Using a different library

Any QR code library works. Implement the contract and pass your own generator:

```php
use N1ebieski\KSEFClient\Contracts\Actions\GenerateQRCodes\QRCodeGeneratorInterface;
use N1ebieski\KSEFClient\ValueObjects\QRCodeImage;

final class MyQRCodeGenerator implements QRCodeGeneratorInterface
{
    public function generate(string $data, ?string $label = null): QRCodeImage
    {
        // $label is the caption below the code, null when captions are disabled

        return new QRCodeImage($raw, 'image/svg+xml');
    }
}
```

`QRCodeImage` takes the raw image contents and its mime type, which defaults to `image/png`. The contract expects the raw image, never a data URI - watch out for libraries that encode their output by default, such as `chillerlan/php-qrcode` with `outputBase64` set to `true`.

This also covers `endroid/qr-code` `5.x` if you need to stay on it: implement the contract against its fluent builder yourself. Note that the `5.x` builder is mutable, so build every code from a `clone` of it.

## QRCode value object

`QRCode` now carries the mime type reported by the generator and `__toString()` uses it instead of always producing `data:image/png;base64,`. A `SvgWriter` therefore yields an `image/svg+xml` data URI.

```php
$qrCodes->code1->mimeType;  // new
(string) $qrCodes->code1;   // data:<mimeType>;base64,...
```

`QRCode::from()` takes the mime type as an optional third argument. `$raw` and `$url` are unchanged.

## phpseclib 4

The package now requires `phpseclib/phpseclib: ^4.0`. Since 3.x and 4.x use different namespaces they cannot be installed side by side, so if your application uses phpseclib directly, change `phpseclib3\` to `phpseclib4\`. Nothing in this package's public API exposes phpseclib types.
