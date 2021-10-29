<?php

namespace Test\my127\Workspace\Types;

use my127\Workspace\Tests\IntegrationTestCase;

class SubscriberTest extends IntegrationTestCase
{
    /** @test */
    public function subscriber_script_is_run_when_appropriate_event_is_triggered(): void
    {
        $this->createWorkspaceYml(
            <<<'EOD'
on('custom.event'): |
  #!bash
  echo -n "Hello World"

command('hi'): |
  #!php
  $ws->trigger('custom.event');
EOD
        );

        $this->assertEquals("Hello World", $this->workspaceCommand('hi')->getOutput());
    }

    /** @test */
    public function subscriber_script_is_run_with_env_when_triggered(): void
    {
        $this->createWorkspaceYml(
            <<<'EOD'
on('custom.event'):
  env:
    EXAMPLE: test
  exec: |
    #!bash
    echo -n "Hello World, $EXAMPLE"

command('hi'): |
  #!php
  $ws->trigger('custom.event');
EOD
        );

        $this->assertEquals("Hello World, test", $this->workspaceCommand('hi')->getOutput());
    }

    /** @test */
    public function after_can_be_used_as_a_shorthand_for_event_names_prefixed_with_after(): void
    {
        $this->createWorkspaceYml(
            <<<'EOD'
after('custom.event'): |
  #!bash
  echo -n "Hello World"

command('hi'): |
  #!php
  $ws->trigger('after.custom.event');
EOD
        );

        $this->assertEquals("Hello World", $this->workspaceCommand('hi')->getOutput());
    }

    /** @test */
    public function before_can_be_used_as_a_shorthand_for_event_names_prefixed_with_before(): void
    {
        $this->createWorkspaceYml(
            <<<'EOD'
before('custom.event'): |
  #!bash
  echo -n "Hello World"

command('hi'): |
  #!php
  $ws->trigger('before.custom.event');
EOD
        );

        $this->assertEquals("Hello World", $this->workspaceCommand('hi')->getOutput());
    }
}
