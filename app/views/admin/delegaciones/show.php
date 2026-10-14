<?php
/**
 * Vista: Historial Simple de Partidos de la Delegación
 * Estilo Scoreboard / App Deportiva (Tipo Flashscore / Sofascore)
 * Compatible con PHP 7.3 y AdminLTE 3
 *
 * @var array $delegacion
 * @var array $partidos
 * @var array $torneosParticipantes
 * @var int|null $filtroTorneo
 * @var string|null $filtroEstado
 */

$colorDel = !empty($delegacion['color_representativo']) ? $delegacion['color_representativo'] : '#0066B2';

// Agrupar partidos por Torneo / Disciplina
$partidosPorTorneo = [];
foreach ($partidos as $p) {
    $tId = (int)$p['torneo_id'];
    if (!isset($partidosPorTorneo[$tId])) {
        $partidosPorTorneo[$tId] = [
            'torneo_nombre'     => $p['torneo_nombre'],
            'disciplina_nombre' => $p['disciplina_nombre'],
            'disciplina_icono'  => !empty($p['disciplina_icono']) ? $p['disciplina_icono'] : 'trophy',
            'partidos'          => []
        ];
    }
    $partidosPorTorneo[$tId]['partidos'][] = $p;
}
?>

<style>
/* Estilo Scoreboard Deportivo Simple (Inspirado en SofaScore / Flashscore) */
.scoreboard-container {
    max-width: 820px;
    margin: 0 auto;
    background-color: #0f172a;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0,0,0,0.25);
    color: #ffffff;
}

.scoreboard-header {
    background-color: #1e293b;
    padding: 14px 20px;
    border-bottom: 1px solid #334155;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.scoreboard-group-title {
    background-color: #1e293b;
    padding: 10px 18px;
    font-size: 0.85rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    color: #94a3b8;
    border-bottom: 1px solid #334155;
    display: flex;
    align-items: center;
}

.match-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 18px;
    border-bottom: 1px solid #1e293b;
    transition: background-color 0.2s ease;
    text-decoration: none !important;
    color: #f8fafc !important;
}

.match-item:hover {
    background-color: #1e293b;
    color: #ffffff !important;
}

.team-box {
    display: flex;
    align-items: center;
    flex: 1;
    min-width: 0;
}

.team-box.local {
    justify-content: flex-end;
    text-align: right;
}

.team-box.away {
    justify-content: flex-start;
    text-align: left;
}

.team-name {
    font-size: 0.95rem;
    font-weight: 600;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    padding: 0 12px;
}

.team-name.highlight {
    color: #38bdf8;
    font-weight: 700;
}

.team-badge {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 700;
    color: #ffffff;
    flex-shrink: 0;
    box-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.match-center-box {
    width: 120px;
    flex-shrink: 0;
    text-align: center;
}

.match-score {
    font-size: 1.25rem;
    font-weight: 800;
    letter-spacing: 2px;
    padding: 4px 12px;
    border-radius: 6px;
    display: inline-block;
    background-color: #1e293b;
}

.match-score.winner-local {
    border-left: 3px solid #22c55e;
}

.match-score.winner-away {
    border-right: 3px solid #22c55e;
}

.match-time {
    font-size: 1.1rem;
    font-weight: 700;
    color: #f1f5f9;
}

.match-status-sub {
    font-size: 0.7rem;
    color: #94a3b8;
    margin-top: 2px;
    display: block;
}

.filter-pill {
    padding: 5px 14px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-decoration: none !important;
    margin-right: 6px;
    display: inline-block;
}

.filter-pill.active {
    background-color: #0066B2;
    color: #ffffff !important;
}

.filter-pill.inactive {
    background-color: #1e293b;
    color: #94a3b8 !important;
    border: 1px solid #334155;
}

.filter-pill.inactive:hover {
    background-color: #334155;
    color: #ffffff !important;
}
</style>

<!-- Barra Superior con Botón Volver -->
<div class="container-fluid pt-3 pb-2" style="max-width: 820px;">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <a href="<?php echo BASE_URL; ?>/delegaciones" class="btn btn-sm btn-outline-secondary font-weight-bold">
                <i class="fas fa-arrow-left mr-1"></i> Volver a Delegaciones
            </a>
        </div>
        <div class="text-right">
            <span class="text-muted text-sm">Olimpiadas Inter-Cooperativas</span>
        </div>
    </div>

    <!-- Píldoras de Filtro Simple (Todos / Finalizados / Programados) -->
    <div class="mb-3 d-flex flex-wrap align-items-center justify-content-between">
        <div>
            <a href="<?php echo BASE_URL; ?>/delegaciones/<?php echo (int)$delegacion['id']; ?>" class="filter-pill <?php echo empty($filtroEstado) ? 'active' : 'inactive'; ?>">
                Todos (<?php echo count($partidos); ?>)
            </a>
            <a href="<?php echo BASE_URL; ?>/delegaciones/<?php echo (int)$delegacion['id']; ?>?estado=Finalizado" class="filter-pill <?php echo ($filtroEstado === 'Finalizado') ? 'active' : 'inactive'; ?>">
                Finalizados
            </a>
            <a href="<?php echo BASE_URL; ?>/delegaciones/<?php echo (int)$delegacion['id']; ?>?estado=Programado" class="filter-pill <?php echo ($filtroEstado === 'Programado') ? 'active' : 'inactive'; ?>">
                Próximos
            </a>
        </div>

        <div class="text-muted text-xs mt-1">
            <span class="badge" style="background-color: <?php echo htmlspecialchars($colorDel, ENT_QUOTES, 'UTF-8'); ?>;">&nbsp;</span>
            Mostrando partidos de <strong><?php echo htmlspecialchars($delegacion['nombre'], ENT_QUOTES, 'UTF-8'); ?></strong>
        </div>
    </div>
</div>

<!-- Contenedor Principal: Scoreboard Deportivo -->
<div class="container-fluid pb-5">
    <div class="scoreboard-container">

        <!-- Cabecera Institucional del Scoreboard -->
        <div class="scoreboard-header">
            <div class="d-flex align-items-center">
                <div class="team-badge mr-3" style="background-color: <?php echo htmlspecialchars($colorDel, ENT_QUOTES, 'UTF-8'); ?>; width: 40px; height: 40px; font-size: 1rem;">
                    <?php echo htmlspecialchars(mb_substr($delegacion['nombre'], 0, 2, 'UTF-8'), ENT_QUOTES, 'UTF-8'); ?>
                </div>
                <div>
                    <h5 class="mb-0 font-weight-bold text-white">
                        <?php echo htmlspecialchars($delegacion['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                    </h5>
                    <small class="text-muted">
                        <?php echo !empty($delegacion['alias']) ? htmlspecialchars($delegacion['alias'], ENT_QUOTES, 'UTF-8') : 'Delegación Oficial'; ?>
                    </small>
                </div>
            </div>

            <div class="text-right">
                <span class="badge py-1 px-2" style="background-color: rgba(56, 189, 248, 0.2); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.4);">
                    <i class="fas fa-calendar-check mr-1"></i> Historial de Partidos
                </span>
            </div>
        </div>

        <!-- Lista de Partidos Agrupados por Torneo / Disciplina -->
        <?php if (empty($partidos)): ?>
            <div class="py-5 text-center text-muted">
                <i class="far fa-calendar-times mb-3" style="font-size: 2.5rem; color: #475569;"></i>
                <p class="mb-0 text-slate-400">No hay partidos registrados para esta delegación con el filtro actual.</p>
            </div>
        <?php else: ?>

            <?php foreach ($partidosPorTorneo as $grupo): ?>
                <!-- Cabecera de Disciplina / Torneo -->
                <div class="scoreboard-group-title">
                    <i class="fas fa-<?php echo htmlspecialchars($grupo['disciplina_icono'], ENT_QUOTES, 'UTF-8'); ?> mr-2 text-warning"></i>
                    <span><?php echo htmlspecialchars($grupo['disciplina_nombre'], ENT_QUOTES, 'UTF-8'); ?></span>
                    <span class="mx-2 text-secondary">•</span>
                    <span class="font-weight-normal text-slate-400"><?php echo htmlspecialchars($grupo['torneo_nombre'], ENT_QUOTES, 'UTF-8'); ?></span>
                </div>

                <!-- Filas de Partidos del Torneo -->
                <?php foreach ($grupo['partidos'] as $p): 
                    $esMiDelegacionLocal = ((int)$p['local_delegacion_id'] === (int)$delegacion['id']);
                    $esMiDelegacionVisit = ((int)$p['visitante_delegacion_id'] === (int)$delegacion['id']);
                    
                    $colorLocal = !empty($p['delegacion_local_color']) ? $p['delegacion_local_color'] : '#64748b';
                    $colorVisit = !empty($p['delegacion_visitante_color']) ? $p['delegacion_visitante_color'] : '#64748b';

                    $logoLocal = !empty($p['local_logo']) ? $p['local_logo'] : null;
                    $logoVisit = !empty($p['visitante_logo']) ? $p['visitante_logo'] : null;
                ?>
                    <a href="<?php echo BASE_URL; ?>/partidos/<?php echo (int)$p['id']; ?>" class="match-item" title="Ver detalle del partido">
                        
                        <!-- Equipo Local (Alineado a la derecha como en Flashscore) -->
                        <div class="team-box local">
                            <span class="team-name <?php echo $esMiDelegacionLocal ? 'highlight' : ''; ?>">
                                <?php echo htmlspecialchars($p['local_nombre'], ENT_QUOTES, 'UTF-8'); ?>
                            </span>

                            <?php if ($logoLocal): ?>
                                <img src="<?php echo htmlspecialchars($logoLocal, ENT_QUOTES, 'UTF-8'); ?>" alt="" class="team-badge" style="object-fit:cover;">
                            <?php else: ?>
                                <div class="team-badge" style="background-color: <?php echo htmlspecialchars($colorLocal, ENT_QUOTES, 'UTF-8'); ?>;">
                                    <?php echo htmlspecialchars(mb_substr($p['local_nombre'], 0, 2, 'UTF-8'), ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Centro: Marcador o Horario -->
                        <div class="match-center-box">
                            <?php if ($p['estado'] === 'Finalizado' || $p['estado'] === 'WO'): 
                                $winnerClass = '';
                                if ((int)$p['marcador_local'] > (int)$p['marcador_visitante']) {
                                    $winnerClass = 'winner-local';
                                } elseif ((int)$p['marcador_visitante'] > (int)$p['marcador_local']) {
                                    $winnerClass = 'winner-away';
                                }
                            ?>
                                <div class="match-score <?php echo $winnerClass; ?>">
                                    <?php echo (int)$p['marcador_local']; ?> - <?php echo (int)$p['marcador_visitante']; ?>
                                </div>
                                <span class="match-status-sub">
                                    <?php echo !empty($p['fecha_partido']) ? date('d/m', strtotime($p['fecha_partido'])) : 'Final'; ?>
                                </span>
                            <?php elseif ($p['estado'] === 'En_Juego'): ?>
                                <div class="match-score text-danger" style="border: 1px solid #ef4444;">
                                    <?php echo (int)($p['marcador_local'] ?? 0); ?> - <?php echo (int)($p['marcador_visitante'] ?? 0); ?>
                                </div>
                                <span class="match-status-sub text-danger font-weight-bold">
                                    <i class="fas fa-circle text-xs mr-1"></i> EN VIVO
                                </span>
                            <?php else: ?>
                                <div class="match-time">
                                    <?php echo !empty($p['hora_partido']) ? date('H:i', strtotime($p['hora_partido'])) : '--:--'; ?>
                                </div>
                                <span class="match-status-sub">
                                    <?php echo !empty($p['fecha_partido']) ? date('d/m/Y', strtotime($p['fecha_partido'])) : 'Por jugar'; ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Equipo Visitante (Alineado a la izquierda como en Flashscore) -->
                        <div class="team-box away">
                            <?php if ($logoVisit): ?>
                                <img src="<?php echo htmlspecialchars($logoVisit, ENT_QUOTES, 'UTF-8'); ?>" alt="" class="team-badge" style="object-fit:cover;">
                            <?php else: ?>
                                <div class="team-badge" style="background-color: <?php echo htmlspecialchars($colorVisit, ENT_QUOTES, 'UTF-8'); ?>;">
                                    <?php echo htmlspecialchars(mb_substr($p['visitante_nombre'], 0, 2, 'UTF-8'), ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                            <?php endif; ?>

                            <span class="team-name <?php echo $esMiDelegacionVisit ? 'highlight' : ''; ?>">
                                <?php echo htmlspecialchars($p['visitante_nombre'], ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </div>

                    </a>
                <?php endforeach; ?>

            <?php endforeach; ?>

        <?php endif; ?>

    </div>
</div>
