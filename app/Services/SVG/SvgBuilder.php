<?php

namespace App\Services\SVG;

use App\Services\Github\DTO\GithubRepositoryDTO;
use App\Services\SVG\DTO\SvgBackgroundDTO;
use App\Services\SVG\DTO\SvgTitleDTO;
use SVG\Nodes\Shapes\SVGRect;
use SVG\Nodes\Structures\SVGDocumentFragment;
use SVG\Nodes\Texts\SVGText;
use SVG\SVG;

class SvgBuilder implements SvgBuilderInterface
{

    protected SvgBackgroundDTO $background;
    protected SvgTitleDTO $title;
    /**
     * @var \App\Services\Github\DTO\GithubRepositoryDTO[]
     */
    protected array $repositories = [];
    protected int $offset = 65;
    protected int $offsetStep = 25;
    protected int $reposInCard = 5;

    /**
     * @inheritDoc
     */
    public function build(): string
    {
        $reposCount = count($this->repositories);

        if ($reposCount < $this->reposInCard) {
            $this->repositories = array_merge($this->repositories, $this->createEmptyRepos(5 - $reposCount));
        }

        $image = new SVG($this->background->getWidth(), $this->background->getHeight());

        $document = $this->buildBackground($image, $this->background);
        $document->addChild($this->buildTitle());

//        $i = 0;
        foreach (array_slice($this->repositories, 0, $this->reposInCard) as $repository) {
//            if ($i++ >= $this->reposInCard) {
//                break;
//            }

            $document->addChild($this->buildRepository($repository, $this->offset));
            $document->addChild($this->buildStarIcon($this->offset));
            $document->addChild($this->buildStars($repository, $this->offset));
            $this->offset += $this->offsetStep;
        }

        return $image->toXMLString();
    }

    protected function createEmptyRepos(int $count): array
    {
        $result = [];

        while ($count-- > 0) {
            $result[] = new GithubRepositoryDTO('-', '-', '-');
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function setBackground(SvgBackgroundDTO $backgroundDTO): void
    {
        $this->background = $backgroundDTO;
    }

    /**
     * @inheritDoc
     */
    public function setTitle(SvgTitleDTO $titleDTO): void
    {
        $this->title = $titleDTO;
    }

    /**
     * @inheritDoc
     */
    public function addRepo(GithubRepositoryDTO $repositoryDTO): void
    {
        $this->repositories[] = $repositoryDTO;
    }

    protected function buildBackground(
        SVG $image,
        SvgBackgroundDTO $backgroundDTO
    ): SVGDocumentFragment {
        $document = $image->getDocument();

        $square = new SVGRect(0, 0, $backgroundDTO->getWidth(), $backgroundDTO->getHeight());
        $square->setRY(4.5);
        $square->setStyle('fill', $backgroundDTO->getColor());
        $square->setStyle('stroke', '#E4E2E2');

        return $document->addChild($square);
    }

    protected function buildTitle(): SVGText
    {
        $text = new SVGText($this->title->getText(), $this->title->getXCoordinate(), $this->title->getYCoordinate());
        $text->setStyle('fill', '#2f80ed');
        $text->setStyle('font-weight', '600');
        $text->setStyle('transform', 'translate(0, 25)');
        $text->setStyle('animation-delay', '900ms');
//        $text->setFont(
//            new SVGFont(
//                "'Ubuntu', sans-serif",
//                'https://fonts.googleapis.com/css2?family=Ubuntu:wght@600&display=swap'
//            )
//        );
        $text->setSize('18px');

        return $text;
    }

    protected function buildRepository(GithubRepositoryDTO $repository, int $offset): SVGText
    {
        $text = new SVGText($repository->getName(), 25, $offset);
        $text->setStyle('fill', '#333');
//        $text->setFont(
//            new SVGFont(
//                "'Ubuntu', sans-serif",
//                'https://fonts.googleapis.com/css2?family=Ubuntu:wght@600&display=swap'
//            )
//        );
        $text->setStyle('font-weight', '600');

        $text->setSize('14px');

        return $text;
    }

    protected function buildStarIcon($offset): SVGDocumentFragment
    {
        $star = SVG::fromString(new Star())->getDocument();
        $star->setAttribute('x', 400);
        $star->setAttribute('y', $offset - 13);
        $star->setStyle('fill', '#586069');

        return $star;
    }

    protected function buildStars(GithubRepositoryDTO $repository, $offset): SVGText
    {
        $text = new SVGText($repository->getStars(), 420, $offset);
        $text->setStyle('fill', '#333');
//        $text->setFont(
//            new SVGFont(
//                "'Open Sans', sans-serif",
//                'https://fonts.googleapis.com/css2?family=Open+Sans:wght@700&display=swap'
//            )
//        );
        $text->setSize('14px');
        $text->setStyle('font-weight', '600');

        return $text;
    }
}
