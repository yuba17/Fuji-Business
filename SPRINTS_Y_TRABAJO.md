# Strategos - Plan de Sprints y Reparto de Trabajo

## 📊 Resumen Ejecutivo

Este documento detalla la planificación de sprints, tareas y el progreso de implementación de la plataforma **Strategos**.

---

## 🎯 Estado Actual del Proyecto

### ✅ Completado

1. **Análisis del PRD**
   - ✅ Análisis completo del PRD original
   - ✅ Identificación de mejoras y recomendaciones
   - ✅ Documento de análisis con 871 líneas de recomendaciones
   - ✅ Ajuste de stack tecnológico (Blade + Alpine.js + Livewire)

2. **Creación de Modelos Base**
   - ✅ 20 modelos creados con sus migraciones:
     - Role, Area, Plan, PlanType, PlanVersion, PlanSection
     - Kpi, KpiHistory
     - Milestone, MilestoneDependency
     - Task, TaskDependency
     - Risk, RiskMitigationAction
     - Decision
     - Tag, Taggable
     - Dashboard, DashboardWidget
     - Scenario

### ⚠️ Pendiente de Implementar

- Migraciones completas con todos los campos
- Modelos con relaciones y métodos
- Policies de autorización
- Componentes Blade base
- Layouts
- Controladores y rutas
- Componentes Livewire
- Vistas Blade
- **Modelos faltantes: Cliente, Proyecto** (identificado por el usuario)

---

## 📅 Planificación de Sprints

### Sprint 0: Fundación y Setup (En Progreso)

**Duración estimada:** 3-5 días

**Objetivos:**
- Completar modelo de datos
- Configurar base de datos
- Establecer estructura de carpetas
- Crear componentes UI base

**Tareas:**

#### Tarea 0.1: Modelos y Migraciones Base ✅ (Parcial)
- [x] Crear modelos: Role, Area, Plan, PlanType, PlanVersion, PlanSection
- [x] Crear modelos: Kpi, KpiHistory, Milestone, MilestoneDependency
- [x] Crear modelos: Task, TaskDependency, Risk, RiskMitigationAction
- [x] Crear modelos: Decision, Tag, Taggable, Dashboard, DashboardWidget, Scenario
- [ ] **Crear modelos: Client, Project** (NUEVO - identificado)
- [ ] Completar migraciones con todos los campos necesarios
- [ ] Definir relaciones en modelos
- [ ] Crear seeders para datos iniciales

#### Tarea 0.2: Sistema de Roles y Permisos
- [ ] Migración de roles (director, manager, tecnico, visualizacion)
- [ ] Tabla pivot user_role
- [ ] Tabla pivot user_area (para managers)
- [ ] Modelo Role con métodos helper
- [ ] Middleware para verificación de roles
- [ ] Helper functions para verificación de permisos

#### Tarea 0.3: Componentes Blade Base
- [ ] Componente UI: Card
- [ ] Componente UI: Modal
- [ ] Componente UI: Badge
- [ ] Componente UI: Button
- [ ] Componente UI: Input
- [ ] Componente UI: Select
- [ ] Componente UI: Textarea
- [ ] Componente UI: Table
- [ ] Componente UI: Alert/Notification

#### Tarea 0.4: Layouts Base
- [ ] Layout: app.blade.php (principal)
- [ ] Layout: dashboard.blade.php
- [ ] Layout: presentation.blade.php (modo comité)
- [ ] Partial: navigation.blade.php
- [ ] Partial: sidebar.blade.php
- [ ] Partial: footer.blade.php

#### Tarea 0.5: Configuración y Seeders
- [ ] Seeder: Roles
- [ ] Seeder: PlanTypes
- [ ] Seeder: Usuarios de prueba
- [ ] Seeder: Áreas de ejemplo
- [ ] Factory: User
- [ ] Factory: Plan
- [ ] Factory: Area

---

### Sprint 1: Autenticación y Autorización

**Duración estimada:** 2-3 días

**Objetivos:**
- Completar sistema de autenticación
- Implementar sistema de permisos granular
- Crear middleware de autorización

**Tareas:**

#### Tarea 1.1: Policies de Autorización
- [ ] PlanPolicy
- [ ] AreaPolicy
- [ ] KpiPolicy
- [ ] TaskPolicy
- [ ] RiskPolicy
- [ ] DecisionPolicy
- [ ] DashboardPolicy

#### Tarea 1.2: Middleware y Helpers
- [ ] Middleware: CheckRole
- [ ] Middleware: CheckPermission
- [ ] Helper: canAccessPlan()
- [ ] Helper: canEditPlan()
- [ ] Helper: canApprovePlan()

#### Tarea 1.3: Actualizar User Model
- [ ] Relación: roles()
- [ ] Relación: areas() (para managers)
- [ ] Método: hasRole()
- [ ] Método: hasAnyRole()
- [ ] Método: can()
- [ ] Scope: directors(), managers(), tecnicos()

---

### Sprint 2: Gestión de Planes (MVP Core)

**Duración estimada:** 5-7 días

**Objetivos:**
- CRUD completo de planes
- Sistema de versionado
- Plantillas de planes
- Estados y transiciones

**Tareas:**

#### Tarea 2.1: Controladores y Rutas
- [ ] PlanController (index, create, store, show, edit, update, destroy)
- [ ] PlanVersionController (show, restore, compare)
- [ ] Rutas web para planes
- [ ] Rutas API (si se necesita)

#### Tarea 2.2: Vistas Blade - Planes
- [ ] Vista: plans/index.blade.php (lista de planes)
- [ ] Vista: plans/create.blade.php (crear plan)
- [ ] Vista: plans/show.blade.php (ver plan)
- [ ] Vista: plans/edit.blade.php (editar plan)
- [ ] Vista: plans/versions.blade.php (historial de versiones)
- [ ] Vista: plans/compare.blade.php (comparar versiones)

#### Tarea 2.3: Componentes Livewire - Planes
- [ ] PlanList (lista reactiva con filtros)
- [ ] PlanEditor (editor de plan con secciones)
- [ ] PlanSectionEditor (editor de sección individual)
- [ ] PlanStatusChanger (cambio de estado con validaciones)

#### Tarea 2.4: Sistema de Versionado
- [ ] Service: PlanVersionService
- [ ] Action: CreatePlanVersion
- [ ] Action: RestorePlanVersion
- [ ] Método: compareVersions()
- [ ] Vista: diff entre versiones

#### Tarea 2.5: Plantillas de Planes
- [ ] Seeder: Plantillas base (Negocio, Comercial, Desarrollo Interno, Área, Equipo)
- [ ] Service: PlanTemplateService
- [ ] Vista: selector de plantilla al crear plan

---

### Sprint 3: Gestión de KPIs

**Duración estimada:** 3-4 días

**Objetivos:**
- CRUD de KPIs
- Histórico de valores
- Cálculo automático (si aplica)
- Alertas y notificaciones

**Tareas:**

#### Tarea 3.1: Controladores y Rutas - KPIs
- [ ] KpiController
- [ ] KpiHistoryController
- [ ] Rutas web

#### Tarea 3.2: Vistas Blade - KPIs
- [ ] Vista: kpis/index.blade.php
- [ ] Vista: kpis/create.blade.php
- [ ] Vista: kpis/show.blade.php (con gráfico histórico)
- [ ] Vista: kpis/edit.blade.php

#### Tarea 3.3: Componentes Livewire - KPIs
- [ ] KpiCard (tarjeta de KPI con semáforo)
- [ ] KpiChart (gráfico de evolución)
- [ ] KpiList (lista con filtros)
- [ ] KpiUpdater (actualización rápida de valor)

#### Tarea 3.4: Servicios y Acciones
- [ ] Service: KpiCalculationService
- [ ] Action: UpdateKpiValue
- [ ] Action: CreateKpiHistoryEntry
- [ ] Job: CheckKpiThresholds (para alertas)

---

### Sprint 4: Roadmaps y Milestones

**Duración estimada:** 4-5 días

**Objetivos:**
- Gestión de roadmaps
- Hitos con dependencias
- Visualización tipo Gantt
- Alertas de retrasos

**Tareas:**

#### Tarea 4.1: Controladores y Rutas
- [ ] MilestoneController
- [ ] Rutas web

#### Tarea 4.2: Vistas Blade - Roadmaps
- [ ] Vista: roadmaps/show.blade.php (vista Gantt)
- [ ] Vista: milestones/create.blade.php
- [ ] Vista: milestones/edit.blade.php

#### Tarea 4.3: Componentes Livewire - Roadmaps
- [ ] RoadmapViewer (visualización interactiva)
- [ ] MilestoneEditor (editor de hitos)
- [ ] DependencyManager (gestor de dependencias)

#### Tarea 4.4: Servicios
- [ ] Service: RoadmapService
- [ ] Service: DependencyService
- [ ] Método: calculateCriticalPath()
- [ ] Método: checkDelays()

---

### Sprint 5: Gestión de Tareas (Kanban)

**Duración estimada:** 4-5 días

**Objetivos:**
- Tablero Kanban funcional
- Gestión de tareas
- Asignación y seguimiento
- Subtareas

**Tareas:**

#### Tarea 5.1: Controladores y Rutas
- [ ] TaskController
- [ ] Rutas web

#### Tarea 5.2: Componentes Livewire - Kanban
- [ ] TaskKanban (tablero principal con drag & drop)
- [ ] TaskCard (tarjeta de tarea)
- [ ] TaskEditor (modal de edición)
- [ ] TaskList (vista de lista alternativa)

#### Tarea 5.3: Vistas Blade - Tareas
- [ ] Vista: tasks/index.blade.php (con selector de vista)
- [ ] Vista: tasks/show.blade.php (detalle de tarea)

#### Tarea 5.4: Funcionalidades Avanzadas
- [ ] Drag & drop entre columnas
- [ ] Subtareas
- [ ] Adjuntos
- [ ] Comentarios con @menciones
- [ ] Filtros y búsqueda

---

### Sprint 6: Gestión de Riesgos

**Duración estimada:** 4-5 días

**Objetivos:**
- CRUD de riesgos
- Matriz de riesgos
- Planes de mitigación
- Panel de riesgos corporativos

**Tareas:**

#### Tarea 6.1: Controladores y Rutas
- [ ] RiskController
- [ ] RiskMitigationActionController
- [ ] Rutas web

#### Tarea 6.2: Vistas Blade - Riesgos
- [ ] Vista: risks/index.blade.php
- [ ] Vista: risks/create.blade.php
- [ ] Vista: risks/show.blade.php
- [ ] Vista: risks/matrix.blade.php (matriz de riesgos)
- [ ] Vista: risks/corporate.blade.php (panel corporativo)

#### Tarea 6.3: Componentes Livewire - Riesgos
- [ ] RiskMatrix (matriz interactiva)
- [ ] RiskCard (tarjeta de riesgo)
- [ ] RiskEditor (editor de riesgo)
- [ ] MitigationActionList (lista de acciones)

#### Tarea 6.4: Servicios
- [ ] Service: RiskCalculationService
- [ ] Método: calculateRiskLevel()
- [ ] Método: getCriticalRisks()

---

### Sprint 7: Dashboards

**Duración estimada:** 5-6 días

**Objetivos:**
- Dashboard Director
- Dashboard Manager
- Dashboard Visualización
- Personalización de widgets

**Tareas:**

#### Tarea 7.1: Componentes Livewire - Dashboards
- [ ] DirectorDashboard
- [ ] ManagerDashboard
- [ ] VisualizationDashboard
- [ ] DashboardWidget (componente base para widgets)

#### Tarea 7.2: Widgets Específicos
- [ ] Widget: KpiSummary
- [ ] Widget: PlanStatus
- [ ] Widget: RiskHeatmap
- [ ] Widget: RoadmapTimeline
- [ ] Widget: TaskKanban
- [ ] Widget: RecentDecisions
- [ ] Widget: TeamWorkload

#### Tarea 7.3: Vistas Blade - Dashboards
- [ ] Vista: dashboards/director.blade.php
- [ ] Vista: dashboards/manager.blade.php
- [ ] Vista: dashboards/visualization.blade.php
- [ ] Vista: dashboards/customize.blade.php

#### Tarea 7.4: Personalización
- [ ] Sistema de drag & drop para widgets
- [ ] Guardar configuración de dashboard
- [ ] Múltiples dashboards por usuario

---

### Sprint 8: Decision Log

**Duración estimada:** 3-4 días

**Objetivos:**
- Registro de decisiones
- Relaciones con planes, KPIs, riesgos
- Historial y búsqueda

**Tareas:**

#### Tarea 8.1: Controladores y Rutas
- [ ] DecisionController
- [ ] Rutas web

#### Tarea 8.2: Vistas Blade - Decisiones
- [ ] Vista: decisions/index.blade.php
- [ ] Vista: decisions/create.blade.php
- [ ] Vista: decisions/show.blade.php

#### Tarea 8.3: Componentes Livewire
- [ ] DecisionList
- [ ] DecisionEditor
- [ ] DecisionRelations (gestor de relaciones)

---

### Sprint 9: Modo Presentación / Comité

**Duración estimada:** 3-4 días

**Objetivos:**
- Vista fullscreen para presentaciones
- Navegación por teclado
- Exportación a PDF/PPT

**Tareas:**

#### Tarea 9.1: Controladores
- [ ] PresentationController
- [ ] Rutas web

#### Tarea 9.2: Vistas Blade - Presentación
- [ ] Vista: presentation/show.blade.php (modo fullscreen)
- [ ] Vista: presentation/slides.blade.php (diapositivas)

#### Tarea 9.3: Funcionalidades
- [ ] Navegación por teclado (flechas, espacio)
- [ ] Exportación a PDF
- [ ] Exportación a PowerPoint (usando PhpPresentation o similar)
- [ ] Modo presentador (con notas)

---

### Sprint 10: Tagging y Búsqueda

**Duración estimada:** 2-3 días

**Objetivos:**
- Sistema de etiquetas
- Búsqueda avanzada
- Filtros transversales

**Tareas:**

#### Tarea 10.1: Controladores
- [ ] TagController
- [ ] SearchController
- [ ] Rutas web

#### Tarea 10.2: Componentes Livewire
- [ ] TagManager (gestor de etiquetas)
- [ ] AdvancedSearch (búsqueda avanzada)
- [ ] TagFilter (filtro por etiquetas)

#### Tarea 10.3: Servicios
- [ ] Service: TagService
- [ ] Service: SearchService
- [ ] Integración con Laravel Scout (opcional)

---

### Sprint 11: Scenario Builder (Fase Avanzada)

**Duración estimada:** 6-8 días

**Objetivos:**
- Simulación de escenarios
- Comparación de escenarios
- Cálculo de impactos

**Tareas:**

#### Tarea 11.1: Controladores
- [ ] ScenarioController
- [ ] Rutas web

#### Tarea 11.2: Componentes Livewire
- [ ] ScenarioBuilder (constructor de escenarios)
- [ ] ScenarioComparison (comparación lado a lado)
- [ ] ScenarioResults (resultados de simulación)

#### Tarea 11.3: Servicios
- [ ] Service: ScenarioSimulationService
- [ ] Método: simulateBudgetChange()
- [ ] Método: simulateTeamChange()
- [ ] Método: simulateDelay()
- [ ] Método: calculateImpact()

---

### Sprint 12: Clientes y Proyectos (NUEVO)

**Duración estimada:** 4-5 días

**Objetivos:**
- Gestión de clientes
- Gestión de proyectos
- Relación con planes comerciales
- Análisis sectorial

**Tareas:**

#### Tarea 12.1: Modelos y Migraciones
- [ ] Modelo: Client
- [ ] Modelo: Project
- [ ] Migración: clients (nombre, sector_economico, tamaño, ubicación, etc.)
- [ ] Migración: projects (nombre, cliente_id, plan_comercial_id, estado, fechas, etc.)
- [ ] Relaciones: Client -> Projects, Project -> Plan

#### Tarea 12.2: Controladores y Rutas
- [ ] ClientController
- [ ] ProjectController
- [ ] Rutas web

#### Tarea 12.3: Vistas Blade
- [ ] Vista: clients/index.blade.php
- [ ] Vista: clients/create.blade.php
- [ ] Vista: clients/show.blade.php (con proyectos asociados)
- [ ] Vista: projects/index.blade.php
- [ ] Vista: projects/create.blade.php
- [ ] Vista: projects/show.blade.php

#### Tarea 12.4: Componentes Livewire
- [ ] ClientList
- [ ] ProjectList
- [ ] SectorAnalysis (análisis por sector económico)
- [ ] ClientProjects (proyectos de un cliente)

#### Tarea 12.5: Integración con Plan Comercial
- [ ] Relación Plan Comercial -> Clientes
- [ ] Relación Plan Comercial -> Proyectos
- [ ] Vista: análisis sectorial en Plan Comercial
- [ ] Métricas comerciales por sector

---

## 📊 Resumen de Esfuerzo

| Sprint | Duración | Prioridad | Estado |
|--------|----------|-----------|--------|
| Sprint 0 | 3-5 días | Crítica | 🟡 En Progreso |
| Sprint 1 | 2-3 días | Crítica | ⚪ Pendiente |
| Sprint 2 | 5-7 días | Crítica | ⚪ Pendiente |
| Sprint 3 | 3-4 días | Alta | ⚪ Pendiente |
| Sprint 4 | 4-5 días | Alta | ⚪ Pendiente |
| Sprint 5 | 4-5 días | Alta | ⚪ Pendiente |
| Sprint 6 | 4-5 días | Alta | ⚪ Pendiente |
| Sprint 7 | 5-6 días | Alta | ⚪ Pendiente |
| Sprint 8 | 3-4 días | Media | ⚪ Pendiente |
| Sprint 9 | 3-4 días | Media | ⚪ Pendiente |
| Sprint 10 | 2-3 días | Media | ⚪ Pendiente |
| Sprint 11 | 6-8 días | Baja | ⚪ Pendiente |
| Sprint 12 | 4-5 días | Alta | ⚪ Pendiente (NUEVO) |

**Total estimado:** 48-62 días de desarrollo

---

## 🎯 Priorización

### MVP (Must Have)
- Sprint 0: Fundación
- Sprint 1: Autenticación
- Sprint 2: Gestión de Planes
- Sprint 3: KPIs
- Sprint 5: Tareas
- Sprint 7: Dashboards básicos
- **Sprint 12: Clientes y Proyectos** (añadido)

### MVP+ (Should Have)
- Sprint 4: Roadmaps
- Sprint 6: Riesgos
- Sprint 8: Decision Log
- Sprint 9: Modo Presentación

### Fase Avanzada (Nice to Have)
- Sprint 10: Tagging
- Sprint 11: Scenario Builder

---

## 📝 Notas Importantes

1. **Modelos Cliente y Proyecto:** Identificados como necesarios para gestionar datos sectoriales y comerciales. Añadidos en Sprint 12.

2. **Dependencias entre sprints:**
   - Sprint 1 depende de Sprint 0
   - Sprint 2-7 dependen de Sprint 1
   - Sprint 8-11 dependen de Sprint 2-7
   - Sprint 12 puede desarrollarse en paralelo con Sprint 2-7

3. **Testing:** Cada sprint debe incluir tests (Pest) para las funcionalidades implementadas.

4. **Documentación:** Actualizar documentación al final de cada sprint.

---

## 🔄 Próximos Pasos Inmediatos

1. ✅ Completar modelos Cliente y Proyecto
2. ✅ Completar todas las migraciones
3. ✅ Implementar relaciones en modelos
4. ✅ Crear seeders básicos
5. ⏭️ Empezar Sprint 0.3: Componentes Blade Base

