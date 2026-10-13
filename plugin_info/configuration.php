<?php
/* This file is part of Jeedom. */
require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
include_file('core', 'authentification', 'php');
if (!isConnect('admin')) {
    include_file('desktop', '404', 'php');
    die();
}

$plugin = plugin::byId('Creality_Box');
$update = $plugin->getUpdate();
sendVarToJS('version', Creality_Box::$_pluginVersion);
?>

<?php include_file('desktop', 'configuration', 'css', 'Creality_Box'); ?>

<form class="form-horizontal" id="configuration_plugin_creality_box">
    <div class="creality-box-config-page">
        <header class="creality-box-config-hero">
            <div class="creality-box-config-identity">
                <div class="creality-box-config-logo">
                    <img src="plugins/Creality_Box/plugin_info/Creality_Box_icon.png" alt="Creality Box">
                </div>
                <div>
                    <span class="creality-box-config-eyebrow">{{Configuration du plugin}}</span>
                    <h2>Creality Box</h2>
                    <p>{{Connectez votre Creality Box à Jeedom et configurez la récupération locale des informations d’impression.}}</p>
                </div>
            </div>
            <div class="creality-box-config-meta">
                <span><i class="fas fa-code-branch"></i> {{Version}} <strong>v<?php echo htmlspecialchars((string) Creality_Box::$_pluginVersion, ENT_QUOTES, 'UTF-8'); ?></strong></span>
                <?php if (is_object($update)) { ?>
                    <span><i class="fas fa-calendar-alt"></i> <?php echo htmlspecialchars((string) $update->getLocalVersion(), ENT_QUOTES, 'UTF-8'); ?></span>
                    <span><i class="fas fa-stream"></i> <?php echo htmlspecialchars((string) $update->getConfiguration('version', 'stable'), ENT_QUOTES, 'UTF-8'); ?></span>
                    <span><i class="fas fa-cloud-download-alt"></i> <?php echo htmlspecialchars((string) $update->getSource(), ENT_QUOTES, 'UTF-8'); ?></span>
                <?php } ?>
            </div>
            <div class="creality-box-config-links">
                <a class="btn btn-sm creality-box-config-link-documentation" target="_blank" rel="noopener noreferrer" href="<?php echo htmlspecialchars((string) $plugin->getDocumentation(), ENT_QUOTES, 'UTF-8'); ?>">
                    <i class="fas fa-book"></i> {{Documentation}}
                </a>
                <a class="btn btn-sm creality-box-config-link-changelog" target="_blank" rel="noopener noreferrer" href="<?php echo htmlspecialchars((string) $plugin->getChangelog(), ENT_QUOTES, 'UTF-8'); ?>">
                    <i class="fas fa-list"></i> {{Changelog}}
                </a>
                <a class="btn btn-sm creality-box-config-link-community" target="_blank" rel="noopener noreferrer" href="https://community.jeedom.com/tag/plugin-creality-box">
                    <i class="fas fa-comments"></i> {{Communauté}}
                </a>
            </div>
        </header>

        <section class="creality-box-config-card">
            <div class="creality-box-config-card-header">
                <i class="fas fa-network-wired"></i>
                <div>
                    <h3>{{Configuration de la Creality Box}}</h3>
                    <p>{{Renseignez l’accès Telnet et le fichier utilisé pour récupérer les informations d’impression.}}</p>
                </div>
            </div>
            <div class="creality-box-config-card-body">
                <div class="form-group">
                    <label class="col-sm-4 control-label">
                        {{Adresse IP de la Creality Box}}
                        <sup><i class="fas fa-question-circle" title="{{Entrez l’adresse IP de la Creality Box.}}"></i></sup>
                    </label>
                    <div class="col-sm-4">
                        <input type="text" class="configKey form-control deviceir" data-l1key="ip">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">
                        {{Port d'écoute Telnet}}
                        <sup><i class="fas fa-question-circle" title="{{Port 23 sauf s’il a été modifié.}}"></i></sup>
                    </label>
                    <div class="col-sm-3">
                        <input type="text" class="configKey form-control" data-l1key="listenport" placeholder="23">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">
                        {{Identifiant}}
                        <sup><i class="fas fa-question-circle" title="{{Entrez l’identifiant. Par défaut : root}}"></i></sup>
                    </label>
                    <div class="col-sm-4">
                        <input type="text" class="configKey form-control deviceir" data-l1key="id" placeholder="root">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">
                        {{Mot de passe}}
                        <sup><i class="fas fa-question-circle" title="{{Entrez le mot de passe. Par défaut : cxswprin}}"></i></sup>
                    </label>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <input type="text" class="inputPassword configKey form-control" data-l1key="password">
                            <span class="input-group-btn">
                                <a class="btn btn-default bt_showPass roundedRight"><i class="fas fa-eye"></i></a>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">
                        {{Fichier de log}}
                        <sup><i class="fas fa-question-circle" title="{{Fichier de log d’où seront récupérées les informations.}}"></i></sup>
                    </label>
                    <div class="col-sm-6">
                        <input type="text" class="configKey form-control" data-l1key="logmqtt" placeholder="/media/mmcblk0p1/creality/log/iotlink.log">
                    </div>
                </div>
            </div>
        </section>

        <section class="creality-box-config-card">
            <div class="creality-box-config-card-header">
                <i class="fas fa-sync-alt"></i>
                <div>
                    <h3>{{Rafraîchissement}}</h3>
                    <p>{{Définissez la fréquence de récupération des informations de la Creality Box.}}</p>
                </div>
            </div>
            <div class="creality-box-config-card-body">
                <div class="form-group">
                    <label class="col-sm-4 control-label">
                        {{Intervalle de rafraîchissement des informations (cron)}}
                        <sup><i class="fas fa-question-circle" title="{{Sélectionnez l’intervalle auquel le plugin récupère les informations. Par défaut : 1 minute.}}"></i></sup>
                    </label>
                    <div class="col-sm-4">
                        <select class="configKey form-control" data-l1key="autorefresh">
                            <option value="* * * * *">{{Toutes les minutes}}</option>
                            <option value="*/2 * * * *">{{Toutes les 2 minutes}}</option>
                            <option value="*/3 * * * *">{{Toutes les 3 minutes}}</option>
                            <option value="*/4 * * * *">{{Toutes les 4 minutes}}</option>
                            <option value="*/5 * * * *">{{Toutes les 5 minutes}}</option>
                            <option value="*/10 * * * *">{{Toutes les 10 minutes}}</option>
                            <option value="*/15 * * * *">{{Toutes les 15 minutes}}</option>
                            <option value="*/30 * * * *">{{Toutes les 30 minutes}}</option>
                            <option value="*/45 * * * *">{{Toutes les 45 minutes}}</option>
                            <option value="*/60 * * * *">{{Toutes les heures}}</option>
                            <option value="never">{{Jamais}}</option>
                        </select>
                    </div>
                </div>
            </div>
        </section>
    </div>
</form>

<?php include_file('desktop', 'configuration', 'js', 'Creality_Box'); ?>