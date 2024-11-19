<?php
/**
 * getFileSize
 * 
 * Gets the file size of a static resource
 *
 * @var modX $modx
 * @var array $scriptProperties
 * 
 * Parameters:
 * @property integer $id (required) - ID of the static resource
 * @property string $format (optional) - Output format: 'raw', 'formatted', or 'both'. Default: 'formatted'
 * 
 * Example usage:
 * [[getFileSize? &id=`123`]]
 * [[getFileSize? &id=`123` &format=`raw`]]
 */

// Get parameters
$resourceId = $modx->getOption('id', $scriptProperties);
$format = $modx->getOption('format', $scriptProperties, 'formatted');

// Validate resource ID
if (!$resourceId) {
    return 'Error: Resource ID not specified';
}

// Get the resource
$resource = $modx->getObject('modStaticResource', $resourceId);
if (!$resource) {
    return 'Error: Resource not found';
}

// Get the source file path
$sourcePath = $resource->get('content');
if (!$sourcePath || !file_exists($sourcePath)) {
    return 'Error: File not found';
}

// Get file size in bytes
$sizeInBytes = filesize($sourcePath);

// Format size to human readable format
if ($sizeInBytes >= 1073741824) {
    $formattedSize = number_format($sizeInBytes / 1073741824, 2) . ' GB';
} elseif ($sizeInBytes >= 1048576) {
    $formattedSize = number_format($sizeInBytes / 1048576, 2) . ' MB';
} elseif ($sizeInBytes >= 1024) {
    $formattedSize = number_format($sizeInBytes / 1024, 2) . ' KB';
} else {
    $formattedSize = $sizeInBytes . ' bytes';
}

// Return based on format parameter
switch ($format) {
    case 'raw':
        return $sizeInBytes;
    case 'both':
        return json_encode([
            'bytes' => $sizeInBytes,
            'formatted' => $formattedSize
        ]);
    case 'formatted':
    default:
        return $formattedSize;
}
