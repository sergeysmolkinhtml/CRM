<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class GenerateImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public int $tries = 5;

    /**
     * The image that should have thumbnails generated.
     *
     * @var \App\Models\Image
     */
    public \App\Models\Image | Image $image;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Image $image)
    {
        $this->image = $image;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() : void
    {
        $generator = ImageGenerator::make(Storage::get('images/' . $this->image->getResourceName()));

        foreach (Image::$supportedSizes as $size) {
            $this->resizeAndSave($generator, $size);
        }
    }

    /**
     * Resizes the image into a fit so it is matches
     * the given size, and saves for the user.
     *
     * @param  \Intervention\Image\Image $generator
     * @param  int $size
     * @return void
     */
    protected function resizeAndSave($generator, $size)
    {
        $generator->fit($size, $size);

        $generator->save(storage_path('app/images/' . $this->image->getResourceName($size . 'x' . $size)));
    }
}
