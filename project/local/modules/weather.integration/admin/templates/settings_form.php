<?php
use Bitrix\Main\Localization\Loc;

/**
 * @var SettingsForm $form
 */
?>

<div class="adm-detail-content">
    <?php if ($form->getErrors()): ?>
        <div class="adm-info-message-wrap adm-info-message-red">
            <div class="adm-info-message">
                <?= implode('<br>', $form->getErrors()) ?>
            </div>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= $APPLICATION->GetCurPage() ?>">
        <?= bitrix_sessid_post() ?>

        <table class="adm-detail-content-table">
            <tbody>
            <tr>
                <td width="40%"><?= Loc::getMessage('WEATHER_API_KEY_LABEL') ?>:</td>
                <td>
                    <label>
                        <input type="text"
                               name="API_KEY"
                               value="<?= htmlspecialcharsbx($form->getSettings()['API_KEY'] ?? '') ?>"
                               size="50"
                               class="adm-input">
                    </label>
                </td>
            </tr>
            <tr>
                <td><?= Loc::getMessage('WEATHER_CITY_LABEL') ?>:</td>
                <td>
                    <label>
                        <input type="text"
                               name="CITY"
                               value="<?= htmlspecialcharsbx($form->getSettings()['CITY'] ?? '') ?>"
                               size="50"
                               class="adm-input">
                    </label>
                </td>
            </tr>
            </tbody>
        </table>

        <div class="adm-detail-toolbar">
            <input type="submit"
                   value="<?= Loc::getMessage('SAVE_BUTTON') ?>"
                   class="adm-btn-save">
        </div>
    </form>
</div>
