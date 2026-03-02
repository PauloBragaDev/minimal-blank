<?php
    namespace Source\Http\Controllers;

    use Source\Infrastructure\View\Seo;
    use Source\Infrastructure\View\Message;
    use Source\Infrastructure\View\View;

    /**
     * Yelloweb | Class BaseController
     *
     * @author Paulo Braga <tecnologia@yelloweb.com.br>
     * @package Source\Http\Controllers
     */
    class BaseController
    {
        protected View $view;
        protected Seo $seo;
        protected Message $message;

        public function __construct(string $pathToViews = null)
        {
            $this->view = new View($pathToViews);
            $this->seo = new Seo();
            $this->message = new Message();
        }
    }