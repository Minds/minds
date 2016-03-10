<?php
namespace Minds\Core\Email;

class Template
{

    protected $template;
    protected $template_path;
    protected $data = [];

    protected $body;
    protected $partials = [];

    /**
     * Constructor
     */
    public function __construct()
    {

    }

    public function setTemplate($template = 'default')
    {
        $this->template = $this->findTemplate($template);
        if(!$this->template){
            $this->template = __MINDS_ROOT__ . '/Components/Email/default.tpl';
        }
        return $this;
    }

    public function setBody($template)
    {
        $this->body = $this->findTemplate($template);
        return $this;
    }

    /**
     * Sets a data key to be used within templates
     * @param mixed $key
     * @param mixed $value
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
     * @param  string $prefix
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

            if(file_exists($file)){
                return $file;
            }

        }

        if (strpos($template, '/') !== 0) {
            $template = __MINDS_ROOT__ . '/Components/Email/' . $template;
        }

        if(file_exists($template)){
            return $template;
        }

        return;

    }

    public function render()
    {
        $body = $this->compile($this->body);
        $template = $this->compile($this->template, ['body'=>$body]);
        return $template;
    }

    /**
     * Compiles a file by injecting variables and executing PHP code
     * @param  string $file
     * @param  array  $localData
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
