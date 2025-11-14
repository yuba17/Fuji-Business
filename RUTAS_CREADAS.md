# Rutas Creadas - Strategos

## 📋 Resumen de Rutas

Todas las rutas están protegidas con el middleware `auth` y agrupadas bajo autenticación.

---

## 🏠 Rutas Generales

| Método | URI | Nombre | Controlador | Descripción |
|--------|-----|--------|-------------|-------------|
| GET | `/` | `home` | Closure | Página de bienvenida |
| GET | `/dashboard` | `dashboard` | DashboardController@index | Dashboard principal (según rol) |

---

## 📊 Rutas de Planes

| Método | URI | Nombre | Controlador | Descripción |
|--------|-----|--------|-------------|-------------|
| GET | `/plans` | `plans.index` | PlanController@index | Lista de planes |
| GET | `/plans/create` | `plans.create` | PlanController@create | Formulario crear plan |
| POST | `/plans` | `plans.store` | PlanController@store | Guardar nuevo plan |
| GET | `/plans/{plan}` | `plans.show` | PlanController@show | Ver plan detallado |
| GET | `/plans/{plan}/edit` | `plans.edit` | PlanController@edit | Formulario editar plan |
| PUT/PATCH | `/plans/{plan}` | `plans.update` | PlanController@update | Actualizar plan |
| DELETE | `/plans/{plan}` | `plans.destroy` | PlanController@destroy | Eliminar plan |

**Acceso:** Director, Manager (solo sus planes)

---

## 📈 Rutas de KPIs

| Método | URI | Nombre | Controlador | Descripción |
|--------|-----|--------|-------------|-------------|
| GET | `/kpis` | `kpis.index` | KpiController@index | Lista de KPIs |
| GET | `/kpis/create` | `kpis.create` | KpiController@create | Formulario crear KPI |
| POST | `/kpis` | `kpis.store` | KpiController@store | Guardar nuevo KPI |
| GET | `/kpis/{kpi}` | `kpis.show` | KpiController@show | Ver KPI detallado |
| GET | `/kpis/{kpi}/edit` | `kpis.edit` | KpiController@edit | Formulario editar KPI |
| PUT/PATCH | `/kpis/{kpi}` | `kpis.update` | KpiController@update | Actualizar KPI |
| DELETE | `/kpis/{kpi}` | `kpis.destroy` | KpiController@destroy | Eliminar KPI |

**Acceso:** Director, Manager

---

## ✅ Rutas de Tareas

| Método | URI | Nombre | Controlador | Descripción |
|--------|-----|--------|-------------|-------------|
| GET | `/tasks` | `tasks.index` | TaskController@index | Lista de tareas (Kanban) |
| GET | `/tasks/create` | `tasks.create` | TaskController@create | Formulario crear tarea |
| POST | `/tasks` | `tasks.store` | TaskController@store | Guardar nueva tarea |
| GET | `/tasks/{task}` | `tasks.show` | TaskController@show | Ver tarea detallada |
| GET | `/tasks/{task}/edit` | `tasks.edit` | TaskController@edit | Formulario editar tarea |
| PUT/PATCH | `/tasks/{task}` | `tasks.update` | TaskController@update | Actualizar tarea |
| DELETE | `/tasks/{task}` | `tasks.destroy` | TaskController@destroy | Eliminar tarea |

**Acceso:** Director, Manager, Técnico (solo sus tareas)

---

## ⚠️ Rutas de Riesgos

| Método | URI | Nombre | Controlador | Descripción |
|--------|-----|--------|-------------|-------------|
| GET | `/risks` | `risks.index` | RiskController@index | Lista de riesgos |
| GET | `/risks/create` | `risks.create` | RiskController@create | Formulario crear riesgo |
| POST | `/risks` | `risks.store` | RiskController@store | Guardar nuevo riesgo |
| GET | `/risks/{risk}` | `risks.show` | RiskController@show | Ver riesgo detallado |
| GET | `/risks/{risk}/edit` | `risks.edit` | RiskController@edit | Formulario editar riesgo |
| PUT/PATCH | `/risks/{risk}` | `risks.update` | RiskController@update | Actualizar riesgo |
| DELETE | `/risks/{risk}` | `risks.destroy` | RiskController@destroy | Eliminar riesgo |

**Acceso:** Director, Manager

---

## 📝 Rutas de Decisiones

| Método | URI | Nombre | Controlador | Descripción |
|--------|-----|--------|-------------|-------------|
| GET | `/decisions` | `decisions.index` | DecisionController@index | Lista de decisiones |
| GET | `/decisions/create` | `decisions.create` | DecisionController@create | Formulario crear decisión |
| POST | `/decisions` | `decisions.store` | DecisionController@store | Guardar nueva decisión |
| GET | `/decisions/{decision}` | `decisions.show` | DecisionController@show | Ver decisión detallada |
| GET | `/decisions/{decision}/edit` | `decisions.edit` | DecisionController@edit | Formulario editar decisión |
| PUT/PATCH | `/decisions/{decision}` | `decisions.update` | DecisionController@update | Actualizar decisión |
| DELETE | `/decisions/{decision}` | `decisions.destroy` | DecisionController@destroy | Eliminar decisión |

**Acceso:** Director, Manager (solo sus áreas)

---

## 👥 Rutas de Clientes

| Método | URI | Nombre | Controlador | Descripción |
|--------|-----|--------|-------------|-------------|
| GET | `/clients` | `clients.index` | ClientController@index | Lista de clientes |
| GET | `/clients/create` | `clients.create` | ClientController@create | Formulario crear cliente |
| POST | `/clients` | `clients.store` | ClientController@store | Guardar nuevo cliente |
| GET | `/clients/{client}` | `clients.show` | ClientController@show | Ver cliente detallado |
| GET | `/clients/{client}/edit` | `clients.edit` | ClientController@edit | Formulario editar cliente |
| PUT/PATCH | `/clients/{client}` | `clients.update` | ClientController@update | Actualizar cliente |
| DELETE | `/clients/{client}` | `clients.destroy` | ClientController@destroy | Eliminar cliente |

**Acceso:** Director, Manager

---

## 🚀 Rutas de Proyectos

| Método | URI | Nombre | Controlador | Descripción |
|--------|-----|--------|-------------|-------------|
| GET | `/projects` | `projects.index` | ProjectController@index | Lista de proyectos |
| GET | `/projects/create` | `projects.create` | ProjectController@create | Formulario crear proyecto |
| POST | `/projects` | `projects.store` | ProjectController@store | Guardar nuevo proyecto |
| GET | `/projects/{project}` | `projects.show` | ProjectController@show | Ver proyecto detallado |
| GET | `/projects/{project}/edit` | `projects.edit` | ProjectController@edit | Formulario editar proyecto |
| PUT/PATCH | `/projects/{project}` | `projects.update` | ProjectController@update | Actualizar proyecto |
| DELETE | `/projects/{project}` | `projects.destroy` | ProjectController@destroy | Eliminar proyecto |

**Acceso:** Director, Manager

---

## ⚙️ Rutas de Configuración

| Método | URI | Nombre | Controlador | Descripción |
|--------|-----|--------|-------------|-------------|
| GET | `/settings/profile` | `profile.edit` | Profile (Livewire) | Editar perfil |
| GET | `/settings/password` | `user-password.edit` | Password (Livewire) | Cambiar contraseña |
| GET | `/settings/appearance` | `appearance.edit` | Appearance (Livewire) | Configuración de apariencia |
| GET | `/settings/two-factor` | `two-factor.show` | TwoFactor (Livewire) | Autenticación de dos factores |

---

## 📌 Notas Importantes

1. **Todas las rutas están protegidas** con middleware `auth`
2. **Las rutas de recursos** (plans, kpis, tasks, etc.) usan Route::resource, que crea automáticamente las 7 rutas RESTful estándar
3. **Los controladores están creados** pero aún no tienen la lógica completa implementada (marcados con TODO)
4. **Las vistas Blade** aún no están creadas (pendiente de implementar)
5. **El acceso por roles** se gestionará mediante Policies (pendiente de implementar)

---

## 🔄 Estado de Implementación

### ✅ Completado
- Rutas definidas en `web.php`
- Controladores creados (estructura básica)
- DashboardController con lógica de redirección por rol
- PlanController con métodos básicos

### ⚠️ Pendiente
- Implementar lógica completa en controladores
- Crear vistas Blade para cada ruta
- Implementar Policies de autorización
- Validación de formularios
- Acciones de negocio (Services/Actions)

---

## 🎯 Próximos Pasos

1. Crear vistas Blade para cada ruta
2. Implementar validación en controladores
3. Crear Policies de autorización
4. Implementar Services/Actions para lógica de negocio
5. Añadir rutas adicionales (versiones, comparación, etc.)

