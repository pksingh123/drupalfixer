<?php

namespace Drupal\Tests\extlink\FunctionalJavascript;

/**
 * Testing the basic functionality of External Links.
 *
 * @group Extlink
 */
class ExtlinkTest extends ExtlinkTestBase {

  /**
   * Checks to see if external link gets extlink svg.
   */
  public function testExtlink() {
    // Login.
    $this->drupalLogin($this->adminUser);

    // Create a node with an external link.
    $settings = [
      'type' => 'page',
      'title' => 'test page',
      'body' => [
        [
          'value' => '<p><a href="http://google.com">Google!</a></p><p><a href="mailto:someone@example.com">Send Mail</a></p>',
          'format' => $this->emptyFormat->id(),
        ],
      ],
    ];
    $node = $this->drupalCreateNode($settings);

    // Get the page.
    $this->drupalGet($node->toUrl());
    $page = $this->getSession()->getPage();
    $this->assertTrue($page->hasLink('Google!'));
    $this->assertTrue($page->hasLink('Send Mail'));

    // Test that the page has the external link svg.
    $externalLink = $page->find('xpath', self::EXTLINK_EXT_XPATH);
    $this->assertTrue(!is_null($externalLink) && $externalLink->isVisible(), 'External Link Exists.');

    // Test that the page has the Mailto external link svg.
    $mailToLink = $page->find('xpath', self::EXTLINK_MAILTO_XPATH);
    $this->assertTrue(!is_null($mailToLink) && $mailToLink->isVisible(), 'External Link MailTo Exists.');
  }

  /**
   * Checks to see if an image link gets extlink svg.
   */
  public function testExtlinkImg() {
    // Login.
    $this->drupalLogin($this->adminUser);

    $this->config('extlink.settings')->set('extlink_img_class', TRUE)->save();
    $test_image = current($this->drupalGetTestFiles('image'));
    $image_file_path = \Drupal::service('file_system')->realpath($test_image->uri);

    // Create a node with an external link on an image.
    $settings = [
      'type' => 'page',
      'title' => 'test page',
      'body' => [
        [
          'value' => '<p><a href="http://google.com"><img src="' . $image_file_path . '" alt="Google!" /></a></p>',
          'format' => $this->emptyFormat->id(),
        ],
      ],
    ];
    $node = $this->drupalCreateNode($settings);

    // Get page.
    $this->drupalGet($node->toUrl());
    $page = $this->getSession()->getPage();

    $this->assertTrue($page->hasLink('Google!'));

    // Test that the page has the external link svg.
    $externalLink = $page->find('xpath', self::EXTLINK_EXT_XPATH);
    $this->assertTrue(!is_null($externalLink) && $externalLink->isVisible(), 'External Link Exists.');
  }

  /**
   * Checks to see if external link works correctly when disabled.
   */
  public function testExtlinkDisabled() {
    // Disable Extlink.
    $this->config('extlink.settings')->set('extlink_class', '0')->save();
    $this->config('extlink.settings')->set('extlink_mailto_class', '0')->save();

    // Login.
    $this->drupalLogin($this->adminUser);

    // Create a node with an external link.
    $settings = [
      'type' => 'page',
      'title' => 'test page',
      'body' => [
        [
          'value' => '<p><a href="http://google.com">Google!</a></p><p><a href="mailto:someone@example.com">Send Mail</a></p>',
          'format' => $this->emptyFormat->id(),
        ],
      ],
    ];
    $node = $this->drupalCreateNode($settings);

    // Get the page.
    $this->drupalGet($node->toUrl());
    $page = $this->getSession()->getPage();
    $this->assertTrue($page->hasLink('Google!'));
    $this->assertTrue($page->hasLink('Send Mail'));

    // Test that the page has the external link svg.
    $externalLink = $page->find('xpath', self::EXTLINK_EXT_XPATH);
    $this->assertTrue(is_null($externalLink), 'External Link does not exist.');

    // Test that the page has the Mailto external link svg.
    $mailToLink = $page->find('xpath', self::EXTLINK_MAILTO_XPATH);
    $this->assertTrue(is_null($mailToLink), 'External Link MailTo does not exist.');
  }

  /**
   * Checks to see if external link works with an extended set of links.
   */
  public function testExtlinkDomainMatching() {
    // Login.
    $this->drupalLogin($this->adminUser);

    $domains = [
      'http://www.example.com',
      'http://www.example.com:8080',
      'http://www.example.co.uk',
      'http://test.example.com',
      'http://example.com',
      'http://www.whatever.com',
      'http://www.domain.org',
      'http://www.domain.nl',
      'http://www.domain.de',
      'http://www.auspigs.com',
      'http://www.usapigs.com',
      'http://user:password@example.com',
    ];

    // Build the html for the page.
    $node_html = '';
    foreach ($domains as $item) {
      $node_html .= '<p><a href="' . $item . '">' . $item . '</a></p><p>';
    }

    // Create the node.
    $settings = [
      'type' => 'page',
      'title' => 'test page',
      'body' => [
        [
          'value' => $node_html,
          'format' => $this->emptyFormat->id(),
        ],
      ],
    ];
    $node = $this->drupalCreateNode($settings);

    // Get the page.
    $this->drupalGet($node->toUrl());
    $page = $this->getSession()->getPage();

    // Test that the page has an external link on each link.
    foreach ($domains as $item) {
      $externalLink = $page->findLink($item);
      $this->assertTrue($externalLink->hasAttribute('data-extlink'), 'External Link failed for "' . $item . '"');
    }

  }

  /**
   * Checks to see if external link works with an extended set of links.
   */
  public function testExtlinkDomainMatchingExcludeSubDomainsEnabled() {
    $this->config('extlink.settings')->set('extlink_subdomains', TRUE)->save();
    $this->testExtlinkDomainMatching();
  }

  /**
   * Checks to see if external link font awesome works.
   */
  public function testExtlinkUseFontAwesome() {
    // Enable Use Font Awesome.
    $this->config('extlink.settings')->set('extlink_use_font_awesome', TRUE)->save();

    // Login.
    $this->drupalLogin($this->adminUser);

    // Create a node with an external link.
    $settings = [
      'type' => 'page',
      'title' => 'test page',
      'body' => [
        [
          'value' => '<p><a href="http://google.com">Google!</a></p><p><a href="mailto:someone@example.com">Send Mail</a></p>',
          'format' => $this->emptyFormat->id(),
        ],
      ],
    ];
    $node = $this->drupalCreateNode($settings);

    // Get the page.
    $this->drupalGet($node->toUrl());
    $page = $this->getSession()->getPage();
    $this->assertTrue($page->hasLink('Google!'));
    $this->assertTrue($page->hasLink('Send Mail'));

    // Test that the page has the external link span.
    $this->assertSession()->elementExists('css', 'span.fa-external-link');

    // Test that the page has the Mailto external link span.
    $this->assertSession()->elementExists('css', 'span.fa-envelope-o');
  }

}
