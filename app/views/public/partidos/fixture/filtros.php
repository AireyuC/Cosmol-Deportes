<?php
/**
 * Submódulo: Barra Unificada de Filtros de Fixture
 * Botones de filtrado rápido por fecha (sin contadores) y selector de disciplina
 * Compatible con PHP 7.3
 */
?>
<div class="fixture-filter-card shadow-sm mb-4">
    <div class="p-3">
        <div class="row align-items-center justify-content-between">
            <!-- Lado Izquierdo: Botones Rápidos por Día (Sin contadores) -->
            <div class="col-lg-6 col-12 mb-3 mb-lg-0">
                <div class="d-flex align-items-center flex-wrap">
                    <span class="text-muted font-weight-bold mr-2 text-sm d-none d-md-inline" style="white-space: nowrap;">
                        <i class="far fa-calendar-alt text-primary mr-1"></i> Fecha:
                    </span>
                    <div class="match-day-filters">
                        <button type="button" class="match-day-btn" data-day-filter="ayer">
                            Ayer
                        </button>
                        <button type="button" class="match-day-btn" data-day-filter="hoy">
                            Hoy
                        </button>
                        <button type="button" class="match-day-btn" data-day-filter="manana">
                            Mañana
                        </button>
                        <button type="button" class="match-day-btn active" data-day-filter="todos">
                            Todos
                        </button>
                    </div>
                </div>
            </div>

            <!-- Lado Derecho: Selector de Disciplina Integrado y Armonioso -->
            <div class="col-lg-6 col-12">
                <form action="<?php echo BASE_URL; ?>/portal/fixture" method="get" class="d-flex align-items-center justify-content-lg-end">
                    <label class="font-weight-bold text-muted text-sm mr-2 mb-0 d-none d-sm-inline" style="white-space: nowrap;">
                        <i class="fas fa-filter text-primary mr-1"></i> Disciplina:
                    </label>
                    <div class="input-group input-group-sm" style="max-width: 290px;">
                        <select name="disciplina_id" class="custom-select custom-select-sm" onchange="this.form.submit()" style="font-weight: 600;">
                            <option value="">Todas las disciplinas</option>
                            <?php foreach ($disciplinas as $d): ?>
                                <option value="<?php echo (int)$d['id']; ?>" <?php echo ($disciplinaFiltro === (int)$d['id']) ? 'selected' : ''; ?>>
                                    <?php echo e($d['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if ($disciplinaFiltro): ?>
                            <div class="input-group-append">
                                <a href="<?php echo BASE_URL; ?>/portal/fixture" class="btn btn-outline-secondary btn-sm" title="Quitar filtro de disciplina">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
