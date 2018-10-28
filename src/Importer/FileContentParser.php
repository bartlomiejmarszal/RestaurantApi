<?php
namespace App\Importer;

class FileContentParser
{
    /**
     * @return array
     *
     * @throws \JsonException
     */
    public function getParsedFileContent(): array
    {
        $content = sprintf('{"data": [%s]}', $this->getFileContent());
        $parsedFileContent = json_decode($content, true);
        if (json_last_error()) {
            throw new \JsonException(json_last_error_msg());
        }

        return $parsedFileContent;
    }

    protected function getFileContent(): string
    {
        return file_get_contents(getenv('IMPORT_FILE'));
    }
}