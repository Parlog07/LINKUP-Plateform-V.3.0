<?php

// app/Services/ContentModerationService.php

namespace App\Services;

class ContentModerationService
{

protected array $words;

  public function __construct()
  {
    $this->words = config('mcp.forbiddenWords', []);
  }

    public function analyze(?string $content): array
    {


        if (!$content){
          return ['status' => 'approved', 'reason' => null];
        }

        foreach ($this->words as $word) {
            if (preg_match('/\b' . preg_quote($word, '/') . '\b/i', $content)) {
                return [
                    'status' => 'pending', 
                    'reason' => "Detected forbidden word: {$word}",
                ];
            }
        }

        return [
            'status' => 'approved',
            'reason' => null,
        ];
    }
}
