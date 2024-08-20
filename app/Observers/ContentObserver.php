<?php

namespace App\Observers;

use App\Jobs\UploadVideoNoVimeo;
use App\Models\Content;
use App\Notifications\UserNotification;
use Illuminate\Http\UploadedFile;

use Vimeo\Laravel\Facades\Vimeo as VimeoUpload;
use Vimeo\Vimeo;

class ContentObserver
{
    /**
     * Handle the Content "created" event.
     */
    public function created(Content $content): void
    {
        if ($content->file_storage_type == 'vimeo') {
            dispatch(new UploadVideoNoVimeo($content, auth()->user()));
        }
    }

    /**
     * Handle the Content "updated" event.
     */
    public function updated(Content $content): void
    {
        if ($content->file_storage_type == 'vimeo') {
            $file = new UploadedFile(storage_path('app/'.$content->vimeo_video_id), $content->name.'.mp4');

            $vimeo_project_id = env('VIMEO_PROJECT_ID');
            $vimeo_user_id = env('VIMEO_CLIENT');
            $vimeo_secret = env('VIMEO_SECRET');
            $vimeo_token = env('VIMEO_ACCESS');
            $vimeo = new Vimeo($vimeo_user_id, $vimeo_secret, $vimeo_token);

            $video = VimeoUpload::upload($file, [
                'name' => $content->name,
                'privacy' => [
                    'view' => 'nobody',
                ],
            ]);

            $video_id = str_replace('/videos/', '', $video);

            try {
                $vimeo->request("/me/projects/$vimeo_project_id/videos/$video_id", array(), 'PUT');
            } catch (\Exception $e) {
                dd($e->getMessage());
            }

            Content::withoutEvents(function () use ($content, $video_id) {
                $content->deleteFile($content->vimeo_video_id);
                $content->vimeo_video_id = $video_id;
                $content->save();
            });
        }
    }

    /**
     * Handle the Content "deleted" event.
     */
    public function deleted(Content $content): void
    {
        //
    }

    /**
     * Handle the Content "restored" event.
     */
    public function restored(Content $content): void
    {
        //
    }

    /**
     * Handle the Content "force deleted" event.
     */
    public function forceDeleted(Content $content): void
    {
        //
    }
}
