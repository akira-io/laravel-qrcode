<?php

declare(strict_types=1);

it('arch: source files declare strict types', function (): void {
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(__DIR__.'/../src', FilesystemIterator::SKIP_DOTS),
    );

    $phpFiles = [];

    foreach ($files as $file) {
        if (! $file instanceof SplFileInfo) {
            continue;
        }

        if ($file->getExtension() !== 'php') {
            continue;
        }

        $phpFiles[] = $file->getPathname();
    }

    sort($phpFiles);

    expect($phpFiles)->not->toBeEmpty();

    foreach ($phpFiles as $path) {
        expect(file_get_contents($path))->toStartWith("<?php\n\ndeclare(strict_types=1);");
    }
});
