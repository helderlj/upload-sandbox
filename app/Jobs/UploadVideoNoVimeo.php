<?php

namespace App\Jobs;

use App\Models\Content;
use App\Notifications\UserNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Vimeo\Laravel\VimeoManager;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class UploadVideoNoVimeo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $content;
    protected $user;

    /**
     * Create a new job instance.
     */
    public function __construct(Content $content, $user)
    {
        $this->content = $content;
        $this->user = $user;
    }


    /**
     * Execute the job.
     */
    public function handle(VimeoManager $vimeo): void
    {

        $file = public_path('temp/vimeo/'. $this->content->file_path_vimeo);

        $response = $vimeo->upload($file, [
            'name' => $this->content->name,
            'privacy' => [
                'view' => 'anybody',
            ],
        ]);

        // Obtenha o link do vídeo no Vimeo
        $videoUrl = $response['body']['link'];

        Notification::make()
            ->title('O video foi enviado para o Vimeo')
            ->body('Changes to the post have been saved.')
            ->actions([
                Action::make('view')
                    ->button()
                    ->url($videoUrl, shouldOpenInNewTab: true),

            ])
            ->sendToDatabase($this->user);

    }
}
