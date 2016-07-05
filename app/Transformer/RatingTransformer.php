<?php

namespace App\Transformer;

use League\Fractal\TransformerAbstract;
use App\Rating;

class RatingTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [];

    /**
     * Transform a Rating model into an array
     *
     * @param  Rating   $rating
     * @return array
     */
    public function transform(Rating $rating)
    {
        return [
            'id'    => $rating->id,
            'value' => $rating->value,
            'type'  => $rating->rateable_type,
            'links' => [
                [
                    'rel' => $this->getModelName($rating->rateable_type),
                    'href' => $this->getModelUrl($rating)
                ]
            ],
            'created' => $rating->created_at->toIso8601String(),
            'updated' => $rating->updated_at->toIso8601String()
        ];
    }

    private function getModelName($rateable_type)
    {
        return strtolower(preg_replace("/^App\\\/", '', $rateable_type));
    }

    private function getModelUrl(Rating $rating)
    {
        $author = \App\Author::class;
        $book = \App\Book::class;

        switch ($rating->rateable_type) {
            case $author:
                $name = 'authors.show';
                break;

            case $book:
                $name = 'books.show';
                break;

            default:
                throw new \RuntimeException(sprintf(
                    'Rateable model type for %s is not defined',
                    $rating->rateable_type
                ));
        }

        return route($name, ['id' => $rating->rateable_id]);
    }
}
