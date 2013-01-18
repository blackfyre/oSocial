<?php
/**
 * User: galiczmiklos
 * Date: 2013.01.11.
 * Time: 14:36
 *
 * @package oSocial
 */

/**
 * Kontroller osztály
 *
 * Ez fűz össze mindent ami még nincs összefűzve
 */
class overseer
{
    private $auth = null;
    private $error = null;
    private $smarty = null;
    private $core = null;
    private $docRoot = null;
    private $menu = null;
    private $form = null;
    private $var = null;
    private $admin = null;
    private $userProfile = null;
    private $friends = null;
    private $connector = null;
    private $home = null;
    private $messages = null;

    function __construct()
    {

        //Környezet beállításai
        ini_set("default_charset", "utf-8");
        date_default_timezone_set('Europe/Budapest');


        $this->docRoot = $_SERVER["DOCUMENT_ROOT"] . '/';


        //szükséges osztályok
        $this->error = new errorHandler();
        $this->core = new coreFunctions();


        if (isset($_SESSION['lang'])) {
            $this->setSiteLang($this->core->cleanVar($_SESSION['lang']));
        } else {
            $this->setSiteLang();
        }

        $this->auth = new auth();
        $this->menu = new menuHandler();
        $this->form = new form();
        $this->admin = new adminPanel();
        $this->userProfile = new userProfile();
        $this->friends = new offerFriend();
        $this->connector = new socialConnector();
        $this->home = new homeStream();
        $this->var = new varGetter();
        $this->messages = new messenger();

        // A Smarty legyen mindig az utolsó

        $this->smarty = new Smarty();
        $this->initSmarty();
        $this->setSmartyGlobalElements();


    }

    /**
     * Az oldal nyelvének beállítása
     * @param string $lang
     */
    function setSiteLang($lang = 'hu')
    {

        // Set conditions for gettext
        putenv("LANG=$lang");
        setlocale(LC_ALL, $lang);

        $domain = 'site';
        bindtextdomain($domain, $this->core->cleanPath($this->docRoot . '/locale'));
        textdomain($domain);


        $_SESSION['lang'] = $this->core->cleanVar($lang);


    }

    /**
     * Smart alap konfigurációja
     */
    private function initSmarty()
    {

        $this->smarty->setTemplateDir($this->core->cleanPath($this->docRoot . '/templates'));
        $this->smarty->setCompileDir($this->core->cleanPath($this->docRoot . '/smarty/templates_c'));
        $this->smarty->setConfigDir($this->core->cleanPath($this->docRoot . '/smarty/config'));
        $this->smarty->setCacheDir($this->core->cleanPath($this->docRoot . '/cache/smarty'));
    }

    /**
     * Smarty elemek alapértelmezése, amire szükséges lehet
     */
    private function setSmartyGlobalElements()
    {
        //general elements
        $this->smarty->assign('siteLang', $this->var->getSiteLang());
        $this->smarty->assign('siteTitle', gettext('oSocial'));
        $this->smarty->assign('pageMetaDesc', gettext('FALLBACK_META_DESC'));
        $this->smarty->assign('pageMetaTags', gettext('FALLBACK_META_TAGS'));
        $this->smarty->assign('sideMenu', null);
        $this->smarty->assign('quickSearch', gettext('QUICKSEARCH'));
        $this->smarty->assign('placeForAds', gettext("TITLE_PLACE_FOR_ADS"));
        $this->smarty->assign('emailPlace', gettext('PLACEHOLDER_EMAIL'));
        $this->smarty->assign('userMenuName', gettext('TITLE_USER_MENU'));
        $this->smarty->assign('messages', null);
        $this->smarty->assign('content', null);

        //$this->smarty->assign('',gettext(''));
    }

    /**
     * A nyitólapi regsiztráció és bejelentkezés kelelése
     */
    private function doLogin()
    {
        if (isset($_POST['submit-login'])) {

            $this->auth->doLogin();
            $this->invoke();

        } elseif (isset($_POST['submit-register'])) {

            if ($this->auth->doRegister()) {
                $this->smarty->display('regThankYou.tpl');
            } else {
                $this->control();
            }

        } else {
            $this->smarty->assign('loginForm', $this->auth->loginForm());
            $this->smarty->assign('registerForm', $this->auth->registerForm());
            $this->smarty->display('pageIndex.tpl');
        }
    }

    /**
     * Alapértelmezett tartalom megjelenítése
     */
    private function displayDefaultContent()
    {
        $this->smarty->display('pageDefault.tpl');
    }

    private function switchLang()
    {
        // TODO: a nyelvváltás "lag"-ját meg kell oldani!
        if (isset($_GET['setlang'])) {
            $this->setSiteLang($_GET['setlang']);
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
        $this->control();
    }

    private function control()
    {

        //var_dump($this->auth->checkLoginSource());

        if ($this->auth->checkLoginSource()) {

            $this->smarty->assign('sideMenu', $this->menu->renderMainMenu());
            $messages = $this->home->renderWaitingMessage();

            if (!isset($_GET['mode'])) {
                $messages .= $this->home->getGlobalMessages();
            }

            $this->smarty->assign('messages', $messages);

            if (isset($_GET['mode'])) {

                $mode = $this->core->cleanVar($_GET['mode']);

                // TODO: jobb megoldást találni, mert így elég nehéz bővíteni a nyelvek számát
                // 1 variáció lehet egy $url[$langVar][$mode] tömbös változat
                // még 1 variáció lehet adatbázis támogatásával működő osztály is...

                if ($mode == 'logout' OR $mode == 'kijelentkezes') {
                    $this->auth->destroyEverything();
                    $this->invoke();
                } elseif ($mode == 'admin') {

                    if (isset($_GET['var1'])) {
                        switch ($_GET['var1']) {
                            case 'activate':
                                $activate = true;
                                break;
                            case 'ban':
                                $activate = false;
                                break;
                            default:
                                $activate = null;
                                break;
                        }

                        $this->admin->processUser($activate, $_GET['var2']);
                    }


                    $this->smarty->assign('content', $this->admin->renderRegistrantsToProcess());
                    $this->smarty->display('pageDefault.tpl');

                } elseif ($mode == 'profilom' OR $mode == 'myprofile') {

                    $this->smarty->assign('content', $this->userProfile->renderCurrentUserProfile());
                    $this->smarty->display('pageDefault.tpl');

                } elseif ($mode == 'profil' OR $mode == 'profile') {

                    // Szükséges lehet megvizsgálni, hogy a $_GET['var1'] létezik -e

                    $uid = $this->core->cleanVar($_GET['var1']);

                    $this->smarty->assign('content', $this->userProfile->renderUserProfile($uid));
                    $this->smarty->display('pageDefault.tpl');

                } elseif ($mode == 'kapcsolataim' OR $mode == 'connections') {
                    $this->smarty->assign('content', $this->friends->renderFriendList());
                    $this->smarty->display('pageDefault.tpl');
                } elseif ($mode == 'connect' OR $mode == 'block' OR $mode == 'want') {

                    //jobban is ki lehetne dolgozni, több feltétel szűréssel a biztosabb működésért
                    if ($mode == 'connect') {
                        $this->connector->setRelationTo($_GET['var1'], 1);
                    } elseif ($mode == 'block') {
                        $this->connector->setRelationTo($_GET['var1'], -1);
                    } else {
                        $this->connector->setRelationTo($_GET['var1'], 0);
                    }

                    $this->smarty->assign('content', $this->friends->renderFriendList());
                    $this->smarty->display('pageDefault.tpl');
                } elseif ($mode == 'users' OR $mode == 'felhasznalok') {
                    $this->smarty->assign('content', $this->friends->renderUserList());
                    $this->smarty->display('pageDefault.tpl');
                } elseif ($mode == 'uzeneteim' OR $mode == 'messages') {

                    if (isset($_POST['submit-email'])) {
                        $this->messages->processMessage();
                    }

                    $this->smarty->assign('content', $this->messages->renderMessages());
                    $this->smarty->display('pageDefault.tpl');
                } else {

                }

            } else {

                $this->displayDefaultContent();
            }
        } else {
            if (isset($_GET['mode'])) {

                $mode = $this->core->cleanVar($_GET['mode']);

                // TODO: jobb megoldást találni, mert így elég nehéz bővíteni a nyelvek számát
                if ($mode == 'logout' OR $mode == 'kijelentkezes') {
                    $this->smarty->display('logout.tpl');
                } else {

                }

            } else {
                $this->doLogin();
            }

        }
    }


    public function invoke()
    {
        $this->switchLang();
    }
}
