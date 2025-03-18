<?php
if (!empty($arResult['WEATHER_DATA'])): ?>
    <div class="weather-widget">
        <h3><?= htmlspecialcharsbx($arResult['WEATHER_DATA']['CITY']) ?></h3>
        <p>Температура: <?= htmlspecialcharsbx($arResult['WEATHER_DATA']['TEMPERATURE']) ?>°C</p>
        <p>Влажность: <?= htmlspecialcharsbx($arResult['WEATHER_DATA']['HUMIDITY']) ?>%</p>
        <p>Обновлено: <?= htmlspecialcharsbx($arResult['WEATHER_DATA']['TIMESTAMP']) ?></p>
    </div>
<?php else: ?>
    <p>Данные о погоде отсутствуют.</p>
<?php endif; ?>