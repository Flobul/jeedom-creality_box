<?php
/* This file is part of Jeedom. */
if (!isConnect('admin')) {
    throw new Exception(__('401 - Accès non autorisé', __FILE__));
}

function crealityBoxHealthEscape($_value)
{
    return htmlspecialchars((string) $_value, ENT_QUOTES, 'UTF-8');
}

$eqLogics = Creality_Box::byType('Creality_Box');
$enabledCount = 0;
$onlineCount = 0;
$printingCount = 0;
foreach ($eqLogics as $healthEqLogic) {
    if (!$healthEqLogic->getIsEnable()) {
        continue;
    }
    $enabledCount++;
    if ($healthEqLogic->getStatus('state') !== 'nok') {
        $onlineCount++;
    }
    $printingCommand = $healthEqLogic->getCmd('info', 'state');
    if (is_object($printingCommand) && (float) $printingCommand->execCmd() > 0) {
        $printingCount++;
    }
}
?>

<?php include_file('desktop', 'health', 'css', 'Creality_Box'); ?>

<div id="crealityBoxHealth">
    <header class="creality-box-health-hero">
        <div class="creality-box-health-hero-icon"><i class="fas fa-heartbeat"></i></div>
        <div class="creality-box-health-hero-copy">
            <span class="creality-box-health-eyebrow">{{Diagnostic du plugin}}</span>
            <h2>{{Santé Creality Box}}</h2>
            <p>{{État des imprimantes, communications et impressions en cours.}}</p>
        </div>
    </header>

    <section class="creality-box-health-summary" aria-label="{{Résumé de la santé Creality Box}}">
        <article class="creality-box-health-summary-card">
            <span class="creality-box-health-summary-icon"><i class="fas fa-cube"></i></span>
            <div><span>{{Appareils}}</span><strong><?php echo count($eqLogics); ?></strong><small>{{configuré(s)}}</small></div>
        </article>
        <article class="creality-box-health-summary-card">
            <span class="creality-box-health-summary-icon"><i class="fas fa-power-off"></i></span>
            <div><span>{{Actifs}}</span><strong><?php echo (int) $enabledCount; ?></strong><small>{{activé(s) dans Jeedom}}</small></div>
        </article>
        <article class="creality-box-health-summary-card">
            <span class="creality-box-health-summary-icon"><i class="fas fa-wifi"></i></span>
            <div><span>{{Joignables}}</span><strong><?php echo (int) $onlineCount; ?></strong><small><?php echo max(0, $enabledCount - $onlineCount); ?> {{en erreur}}</small></div>
        </article>
        <article class="creality-box-health-summary-card">
            <span class="creality-box-health-summary-icon"><i class="fas fa-print"></i></span>
            <div><span>{{Impressions}}</span><strong><?php echo (int) $printingCount; ?></strong><small>{{en cours}}</small></div>
        </article>
    </section>

    <section class="creality-box-health-panel">
        <div class="creality-box-health-panel-header">
            <i class="fas fa-cubes"></i>
            <h3>{{Équipements}}</h3>
        </div>
        <div class="table-responsive">
            <table class="table table-condensed table-striped tablesorter" id="table_healthcreality_Box">
                <thead>
                    <tr>
                        <th>{{Appareil}}</th>
                        <th>{{IP}}</th>
                        <th>{{Nom}}</th>
                        <th>{{Statut}}</th>
                        <th>{{Impression en cours}}</th>
                        <th>{{Début impression}}</th>
                        <th>{{Progression}}</th>
                        <th>{{Dernière communication}}</th>
                        <th>{{Date création}}</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($eqLogics as $eqLogic) {
                        $isOnline = $eqLogic->getStatus('state') !== 'nok';
                        $printing = $eqLogic->getCmd('info', 'state');
                        $isPrinting = is_object($printing) && (float) $printing->execCmd() > 0;
                        $printingValue = $isPrinting ? $printing->execCmd() : '';

                        $printStartTime = $eqLogic->getCmd('info', 'printStartTime');
                        $startTime = 'N/A';
                        if (is_object($printStartTime) && $printStartTime->execCmd() != '0') {
                            $startTime = gmdate('Y-m-d H:i:s', (int) $printStartTime->execCmd());
                        }

                        $printProgress = $eqLogic->getCmd('info', 'printProgress');
                        $progress = 0;
                        if (is_object($printProgress) && (float) $printProgress->execCmd() > 0) {
                            $progress = $printProgress->execCmd();
                        }
                    ?>
                        <tr<?php echo $eqLogic->getIsEnable() ? '' : ' class="creality-box-health-disabled"'; ?>>
                            <td><a href="<?php echo crealityBoxHealthEscape($eqLogic->getLinkToConfiguration()); ?>"><?php echo $eqLogic->getHumanName(true); ?></a></td>
                            <td><span class="label label-info"><?php echo crealityBoxHealthEscape($eqLogic->getConfiguration('IP')); ?></span></td>
                            <td><span class="label label-info"><?php echo crealityBoxHealthEscape($eqLogic->getConfiguration('hostname')); ?></span></td>
                            <td><span class="label label-<?php echo $isOnline ? 'success' : 'danger'; ?>"><?php echo $isOnline ? '{{OK}}' : '{{NOK}}'; ?></span></td>
                            <td><span class="label label-<?php echo $isPrinting ? 'success' : 'default'; ?>"><?php echo $isPrinting ? '{{OUI}} (' . crealityBoxHealthEscape($printingValue) . ')' : '{{NON}}'; ?></span></td>
                            <td><span class="label label-info"><?php echo crealityBoxHealthEscape($startTime); ?></span></td>
                            <td><span class="label label-info"><?php echo crealityBoxHealthEscape($progress); ?> %</span></td>
                            <td><?php echo crealityBoxHealthEscape($eqLogic->getStatus('lastCommunication')); ?></td>
                            <td><?php echo crealityBoxHealthEscape($eqLogic->getConfiguration('createtime')); ?></td>
                        </tr>
                    <?php } ?>
                    <?php if (count($eqLogics) === 0) { ?>
                        <tr><td colspan="9" class="text-center text-muted">{{Aucun équipement Creality Box}}</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
