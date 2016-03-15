<?php
namespace RacingUi;

class TwigExtension extends \Twig_Extension
{
    public function getName() {
        return "racing";
    }

    public function getFilters() {
        return array(
            "secsToMins" => new \Twig_Filter_Function(function ($value) {
                $secsToMins = new \Racing\Filters\SecsToMins();
                $time = $secsToMins->filter($value);
                return $time['minutes'] . ':' . $time['seconds'];
            }),
        );
    }
}
