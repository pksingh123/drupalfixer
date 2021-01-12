<?php

namespace Drupal\length_indicator;

/**
 * Class GetWidthPos.
 *
 * @package Drupal\length_indicator
 */
class GetWidthPos {

  /**
   * Gets the widths and positions for the indicator template.
   *
   * @param int $optimin
   *   The optimum minimum.
   * @param int $optimax
   *   The optimum maximum. This must be larger than $optimin. This is validated
   *   on the widget settings form.
   * @param int $tolerance
   *   The tolerance. This must be smaller than $optimin. This is validated on
   *   the widget settings form.
   *
   * @return array
   *   The widths and positions of the indicators.
   */
  public function getWidthAndPosition($optimin, $optimax, $tolerance) {
    $indicators = [];

    $min = $optimin - $tolerance;
    $max = $optimax + $tolerance;

    $total = $max + $min;

    $width = ($min / $total) * 100;
    $indicators[0]['width'] = $width;
    $indicators[0]['pos'] = 0;
    $indicators[0]['class'] = 'length-indicator__indicator--bad';
    // Adding +1 to make max inclusive.
    $indicators[4]['width'] = $width;
    $indicators[4]['pos'] = $max + 1;
    $indicators[4]['class'] = 'length-indicator__indicator--bad';
    $last = $width;

    $width = ($optimin / $total) * 100;
    $indicators[1]['width'] = $width - $last;
    $indicators[1]['pos'] = $min;
    $indicators[1]['class'] = 'length-indicator__indicator--ok';
    $last = $width;

    $width = ($optimax / $total) * 100;
    $indicators[2]['width'] = $width - $last;
    $indicators[2]['pos'] = $optimin;
    $indicators[2]['class'] = 'length-indicator__indicator--good';
    $last = $width;

    $width = ($max / $total) * 100;
    // Adding +1 to make optimax inclusive.
    $indicators[3]['width'] = $width - $last;
    $indicators[3]['pos'] = $optimax + 1;
    $indicators[3]['class'] = 'length-indicator__indicator--ok';

    ksort($indicators);

    return $indicators;
  }

}
