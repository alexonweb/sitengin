<?


class Site 
{

    public function __construct() 
    {

        $this->pageGenerator();

    }


    /* Метод подгружает JSON-файл параметров сайта и XML-файл
    содержания страницы, после чего загружает необходимый шаблон. */
    private function pageGenerator() 
    {

        $sitejsoncontents = file_get_contents( "data/site.json" );

        $siteobj = json_decode($sitejsoncontents);

        // псевдо-глобальные переменные
        $_site      = $siteobj->site;                 // 
        $_themedir  = "/theme/$_site->theme";         // путь к теме
        $_imgdir    = $_themedir . "/images";         // путь к изображениям
        $_jsdir     = $_themedir . "/js";             // путь к js-скриптам

		// **********************************************************************

        // Подгружаем файл страницы
        $_page = "data" . DIRECTORY_SEPARATOR . "content" . DIRECTORY_SEPARATOR . $this->uri() . ".xml";

        if (file_exists($_page)) 
        {

            $_page = simplexml_load_file($_page);

        } 

        else 

        {

            $_page = simplexml_load_file("data" . DIRECTORY_SEPARATOR . "content" . DIRECTORY_SEPARATOR . "er404.xml");

        }

        include_once "theme" . DIRECTORY_SEPARATOR . $_site->theme . DIRECTORY_SEPARATOR . $_page->template . ".php";

    }


    /* URI - Идентификатор рессурса. 
    Если URL заканчивается на слэш (/) (например, http://www.site.ru/ 
    или http://www.site.ru/about/) метод возвращает значение "index"
    В любых других случаех возрващает окончание URL после доменного имени  */
    private function uri() 
    {

        $uri = urldecode($_SERVER['REQUEST_URI']);

        if ($uri == "/") 
        {

           return "index";

        } 
            elseif (substr($uri, strlen($uri) - 1) == "/") 
        {

           return ( substr($uri, 1) . "index" );

        }
            else
        {

            return substr($uri, 1);

        }

    }


    /* Метод выводит меню */
    private function menu()
    {

        $menujsoncontents = file_get_contents("data" . DIRECTORY_SEPARATOR  . "menu.json");

        $menuobject = json_decode($menujsoncontents);

        echo "<ul>";

        foreach ($menuobject as $slug => $title) 
        {

            // Сбрасываем текущий адрес
            $currentmenuhtmlclass = "";

            // Получаем адрес первого уровня
            // @todo по идее лучше переложить это на метод uri();
             $firstlevelURI = substr( $this->uri(), 0, strlen( $slug ) );
            
            if ($firstlevelURI == $slug) 
            {

                $currentmenuhtmlclass = " class=\"current\"";

            }

            echo    "<li><a href=\"/" . 
                    $slug . "\"". $currentmenuhtmlclass. ">" . rtrim($title) . "</a></li>";
                    // @triky: trim чтобы убрать лишние пробелы в конце строки

        }

        echo "</ul>";

    }

}

?>
