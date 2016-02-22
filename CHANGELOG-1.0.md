#CHANGELOG for 1.0.*
#===================

### 1.0.0 (2016-02-19)
 * initial

### 1.1.0 (2016-02-20)
 * introduce StepperInterface
 * introduce DataGridPaginator to limit display count
 * introduce renderPaginator twig function
 * introduce PaginatorLimitForm to mabage datagrid objects limit per page
 * introduce renderPaginatorLimit twig function
 * introduce "paginator_limit_template" and "paginator_template" into the configuration to manage template override
 * update DataGridContainer to implement StepperInterface
 * update DataGrid stepper to access additional data
 * update 'datagc' to aquire additional data
 * register paginatorLimit form