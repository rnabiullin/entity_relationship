<?php

namespace Drupal\entity_relationship\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Controller for the report page.
 */
class DiagramController extends ControllerBase {

  /**
   * The request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * ReportController constructor.
   *
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   The request stack.
   */
  public function __construct(RequestStack $request_stack) {
    $this->requestStack = $request_stack;

  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('request_stack')
    );
  }

  /**
   * Main method to create full graph of entities.
   *
   * @return array
   *   A render array with a generated diagram.
   */
  public function content() {
    $build = [];
    $request = $this->requestStack->getCurrentRequest();
    $entity_types = $request->query->get('entities');

    $graph = \Drupal::service('entity_relationship.diagram');
    $graph->create($entity_types);
    $graph_string = $graph->generateGraph();

    $build['#attached']['library'][] = 'entity_relationship/entity_relationship';
    $build['#attached']['drupalSettings']['entity_relationship']['dataSVG'] = $graph_string;

    return $build;
  }

}
