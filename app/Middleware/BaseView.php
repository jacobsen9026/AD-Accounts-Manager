<?php namespace App\Middleware;

use CodeIgniter\View\View;

class BaseView extends View {

    public function renderPartial(string $view, array $options = null, $saveData = null): string
    {
        $start = microtime(true);
        if (is_null($saveData))
        {
            $saveData = $this->config->saveData;
        }

        $fileExt                     = pathinfo($view, PATHINFO_EXTENSION);
		
        $realPath                    = empty($fileExt) ? $view . '.php' : $view; // allow Views as .html, .tpl, etc (from CI3)
        $this->renderVars['view']    = $realPath;
        $this->renderVars['options'] = $options;

        // Was it cached?
        if (isset($this->renderVars['options']['cache']))
        {
            $this->renderVars['cacheName'] = $this->renderVars['options']['cache_name'] ?? str_replace('.php', '', $this->renderVars['view']);

            if ($output = cache($this->renderVars['cacheName']))
            {
                $this->logPerformance($this->renderVars['start'], microtime(true), $this->renderVars['view']);
                return $output;
            }
        }

        $this->renderVars['file'] = $this->viewPath . $this->renderVars['view'];

        if (! is_file($this->renderVars['file']))
        {
            $this->renderVars['file'] = $this->loader->locateFile($this->renderVars['view'], 'Views', empty($fileExt) ? 'php' : $fileExt);
        }

        if (! is_null($options)) {
            $this->setData($options);
        }
            
        extract($this->data);

        if (! $saveData)
        {
            $this->data = [];
        }

        ob_start();
        include($this->renderVars['file']);
        $output = ob_get_contents();
        @ob_end_clean();

        $this->logPerformance($start, microtime(true), $this->excerpt($view));

        return $output;
    }

} 