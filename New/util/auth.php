<?php

    const ENC_KEY    = '1029384756z/x.c,vmbn';
    const AUTH_KEY_S = '_authKey_';
    const AUTH_KEY_R = 'k';

    require_once 'db/database.php';
    require_once 'session/session_mgr.php';

    function getEncriptionKey ()
    {
        return sprintf ('%08x', time () & 0xFFFFFF00).ENC_KEY;
    }

    function getAuthKey ()
    {
        if (array_key_exists (AUTH_KEY_S, $_SESSION))
        {
            $authKey = $_SESSION [AUTH_KEY_S];
        }
        else if (array_key_exists (AUTH_KEY_R, $_REQUEST))
        {
            $authKey = $_REQUEST [AUTH_KEY_R];

            $_SESSION [AUTH_KEY_S] = $authKey; // Remember for sequentional calls
        }
        else
        {
            $authKey = NULL;
        }

        return $authKey;
    }

    function checkAuthorizeByKey ($sessionMgr, $authKey)
    {
        if ($authKey && !$sessionMgr->isAuthenticated ())
        {
            $database = new Database ();

            if ($database)
            {
                $data = json_decode (openssl_decrypt (base64_decode ($authKey), 'AES-128-ECB', getEncriptionKey ()), TRUE);

                if (array_key_exists ('un', $data) && array_key_exists ('p', $data))
                {
                    $userInfo = $database->checkCredentials ($data ["un"], $data ["p"]);
                    $userID   = array_key_exists ('id', $userInfo) ? $userInfo ['id'] : NULL;

                    $sessionMgr->setAuthenticationStatus (TRUE);
                    $sessionMgr->setUserID ($userID);
                    $sessionMgr->setAccessTime ();
                }

                $database->close ();
            }
        }
    }
