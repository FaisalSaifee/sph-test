<?php

namespace Drupal\jugaad\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\node\NodeInterface;
use Drupal\jugaad\GetQRService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a block with QR code of purchase link.
 *
 * @Block(
 *   id = "qr_code_block",
 *   admin_label = @Translation("QR code of purchase link")
 * )
 */
class GetQRBlock extends Blockbase implements ContainerFactoryPluginInterface {
  /**
   * Drupal\jugaad\Get QR Code Service definition.
   *
   * @var \Drupal\jugaad\GetQRService
   */
  protected $getQRService;

  /**
   * Constructs a new ControllerBlock object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The configuration factory.
   * @param \Drupal\jugaad\GetQRService $get_qr_service
   *   QR Code Service information.
   */
  public function __construct(
      array $configuration,
      $plugin_id,
      $plugin_definition,
      GetQRService $get_qr_service) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->getQRService = $get_qr_service;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
        $configuration,
        $plugin_id,
        $plugin_definition,
        $container->get('jugaad.jp')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $node = \Drupal::routeMatch()->getParameter('node');
    // Check if it is a node.
    if ($node instanceof NodeInterface) {
      if ($node->bundle() == 'products') {
        $product_url = $node->field_products_link->getValue()[0]['uri'];
        $qr = $this->getQRService->getQr($product_url);

        $build = [
          '#type' => 'inline_template',
          '#template' => "<img src='{{ data }}' />",
          '#context' => [
            'data' => $qr->getDataUri(),
          ],
          '#cache' => [
            'tags' => ['node_list:products'],
          ],
        ];

      }
    }

    return $build;

  }

}
