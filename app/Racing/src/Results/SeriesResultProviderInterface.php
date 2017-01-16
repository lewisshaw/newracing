<?php
namespace Racing\Results;

interface SeriesResultProviderInterface
{
    public function getBySeries(int $seriesId);
}
