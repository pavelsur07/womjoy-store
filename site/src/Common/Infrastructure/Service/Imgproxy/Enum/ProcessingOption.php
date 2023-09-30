<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Service\Imgproxy\Enum;

enum ProcessingOption: string
{
    case WIDTH = 'w';
    case HEIGHT = 'h';
    case RESIZING_TYPE = 'rt';
    case RESIZING_ALGORITHM = 'ra';
    case DPR = 'dpr';
    case ENLARGE = 'el';
    case EXTEND = 'ex';
    case GRAVITY = 'g';
    case CROP = 'c';
    case PADDING = 'pd';
    case TRIM = 't';
    case ROTATE = 'rot';
    case QUALITY = 'q';
    case MAX_BYTES = 'mb';
    case BACKGROUND = 'bg';
    case BACKGROUND_ALPHA = 'bga';
    case ADJUST = 'a';
    case BRIGHTNESS = 'br';
    case CONTRAST = 'co';
    case SATURATION = 'sa';
    case BLUR = 'bl';
    case SHARPEN = 'sh';
    case PIXELATE = 'pix';
    case UNSHARPENING = 'ush';
    case WATERMARK = 'wm';
    case WATERMARK_URL = 'wmu';
    case STYLE = 'st';
    case JPEG_OPTIONS = 'jpgo';
    case PNG_OPTIONS = 'pngo';
    case GIF_OPTIONS = 'gifo';
    case PAGE = 'pg';
    case VIDEO_THUMBNAIL_SECOND = 'vts';
    case PRESET = 'pr';
    case CACHEBUSTER = 'cb';
    case STRIP_METADATA = 'sm';
    case STRIP_COLOR_PROFILE = 'scp';
    case AUTO_ROTATE = 'ar';
    case FILENAME = 'fn';
    case FORMAT = 'f';
}
