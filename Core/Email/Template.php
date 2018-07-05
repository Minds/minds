<?php

namespace Minds\Core\Email;

use Minds\Core\Markdown\Markdown;

class Template
{
    protected $template;
    protected $template_path;
    protected $data = [];

    protected $body;
    protected $partials = [];

    protected $loadFromFile = true;

    protected $useMarkdown = false;

    /** @var Markdown */
    protected $markdown;

    /**
     * Constructor
     * @param Markdown $markdown
     */
    public function __construct($markdown = null)
    {
        $this->markdown = $markdown ?: new Markdown();
        $this->data['cdn_assets_url'] = 'https://cdn-assets.minds.com/front/dist/';
    }

    public function setTemplate($template = 'default')
    {
        $this->template = $this->findTemplate($template);
        if (!$this->template) {
            $this->template = __MINDS_ROOT__ . '/Components/Email/default.tpl';
        }
        return $this;
    }

    public function setBody($template, $fromFile = true)
    {
        $this->body = $fromFile ? $this->findTemplate($template) : $template;
        $this->loadFromFile = (bool) $fromFile;
        return $this;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function toggleMarkdown($value)
    {
        $this->useMarkdown = (bool) $value;
        return $this;
    }

    /**
     * Sets a data key to be used within templates
     * @param mixed $key
     * @param mixed $value
     * @return $this
     */
    public function set($key, $value = null)
    {
        if (!is_array($key)) {
            $this->data[$key] = $value;
            return $this;
        }

        foreach ($key as $singleKey => $value) {
            $this->data[$singleKey] = $value;
        }

        return $this;
    }

    /**
     * Find a template from a path
     * @param  string $template
     * @return string
     */
    protected function findTemplate($template)
    {

        //relative paths
        if (strpos($template, './') === 0) {
            //relative path!

            $trace = debug_backtrace();
            $traceFile = $trace[1]['file'];
            $parts = explode('/', $traceFile);
            array_pop($parts);
            $dir = implode('/', $parts);

            $template = substr($template, 1);
            $file = $dir . $template;

            if (file_exists($file)) {
                return $file;
            }
        }

        if (strpos($template, '/') !== 0) {
            $template = __MINDS_ROOT__ . '/Components/Email/' . $template;
        }

        if (file_exists($template)) {
            return $template;
        }

        return;
    }

    public function render()
    {
        $body = $this->loadFromFile ? $this->compile($this->body) : $this->body;
        if ($this->useMarkdown) {
            $body = $this->markdown->text($body);
        }
        $template = $this->compile($this->template, ['body' => $body]);
        return $template;
    }

    /**
     * Compiles a file by injecting variables and executing PHP code
     * @param  string $file
     * @param  array $vars
     * @return string
     */
    protected function compile($file, $vars = [])
    {
        $vars = array_merge($this->data, $vars);

        ob_start();

        include $file;

        $contents = ob_get_contents();

        ob_end_clean();

        return $contents;
    }
}
