<?php
// Définition des constantes
const STATE_COLORS = array(
    'pending' => array(
        'textColor' => 'rgb(236, 173, 39)',
        'backgroundColor' => 'rgb(249, 235, 204)',
    ),
    'current' => array(
        'textColor' => 'rgb(66, 149, 66)',
        'backgroundColor' => 'rgb(182, 222, 182)',
    ),
    'ended' => array(
        'textColor' => 'rgb(171, 171, 171)',
        'backgroundColor' => 'rgb(226,226,226)',
    ),
);

const STATE_LABELS = array(
    'pending' => array(
        'label' => 'À venir',
    ),
    'current' => array(
        'label' => 'En cours',
    ),
    'ended' => array(
        'label' => 'Terminé',
    ),
);

// Définition des variables
$stateColors = STATE_COLORS;
$stateLabels = STATE_LABELS;