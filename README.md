# DataGrid bundle documentation
### Version: 1.1.0

The DataGrid bundle allow to display a datagrid into twig template.

##### Installation

Register the bundle into app/appKernel.php

```php
// app/AppKernel.php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            [...]
            new Cscfa\Bundle\DataGridBundle\CscfaDataGridBundle(),
        );
        
        [...]
    }
}
```

### Create you'r first datagrid

```php
// in php file
use Cscfa\Bundle\DataGridBundle\Objects\DataGridContainer;
```

The datagrid system use the DataGridContainer class to define the datagrid informations.

Basically, this class is instanciate with a set of data to display, the access method to get the specifically data from each elements, the headers to display and the elements type.

By 'element', we understand the row data container. This element can be an array or an object. By default, the container use each element as array. If you give an array of object, as a doctrine findAll result, you will must specify it by passing DataGridContainer::TYPE_OBJECT as fourth argument of the constructor.

To display data, you'll must specify the access methods to it. By passing an array of string you can inform on the access method of each elements data. If the elements are array, the access method will be an array of key to display. If the elements are objects, the access method will be the getter methods of the objects.

The header argument is an array of string that inform on the header of each column.

```php
	// Asume this code is into a controller
	
	$datas = array(array("element 1.1", "element 1.2"), array("element 2.1", "element 2.2"));
	
	$dataGrid = new DataGridContainer($datas, array(0, 1), array("head1", "head2"), DataGridContainer::TYPE_ARRAY);
	
	$this->render("AcmeBundle:Default:index.html.twig", array("data"=>$dataGrid));
```

And into the twig template :

```twig
	{# in your template file #}
	{{ renderDatagrid(data) }}
```

No one of the arguments are required to instanciate the DataGridContainer class and empty container does not generate exception.

You can instanciate a datagrid with this code : 

```php
	// Asume this code is into a controller
	$dataGrid = new DataGridContainer();
	
	$this->render("AcmeBundle:Default:index.html.twig", array("data"=>$dataGrid));
```

And define each arguments with this :

```php
	// Asume this code is into a controller
	/*
	 * Note we use here the result of a doctrine request
	 * And we specify the the type is object.
	 */
    $manager = $this->getDoctrine()->getManager();
    $repository = $manager->getRepository("Acme\Bundle\AcmeBundle\Entity\Miscellaneous");
    $miscs = $repository->findAll();
            
	$dataGrid = new DataGridContainer();
	
	$dataGrid->setContainer($miscs);
	$dataGrid->setAccessMethods("getName", "getId");
	$dataGrid->setHeader("name", "identity");
	$dataGrid->setType(DataGridContainer::TYPE_OBJECT);
	
	$this->render("AcmeBundle:Default:index.html.twig", array("data"=>$dataGrid));
```

### Advanced use with callbacks

```php
// in php file
use Cscfa\Bundle\DataGridBundle\Objects\DataGridStepper;
```

The datagrid can use callbacks that will be calls by a DataGridStepper into the rendering step by step. Some of this callbacks already exists into the default templates. We can use :

Callback name | description
------------- | -----------
onGridStart | This callback is called before the datagrid
onGridStop | This callback is called after the datagrid
onGrid | This callback is called to render the main datagrid html opening tag attributes, after the tag name and before the tag end
onGridPrepend | This callback is called after the main datagrid html opening tag
onGridAppend | This callback is called before the main datagrid html closing tag 
onHeadStart | This callback is called before the header html opening tag 
onHeadStop | This callback is called after the header html closing tag
onHead | This callback is called to render the header html opening tag attributes
onHeadPrepend | This callback is called after the header html opening tag
onHeadAppend | This callback is called before the header html closing tag
onHeadElementStart | This callback is called before each header element html opening tag 
onHeadElementStop | This callback is called after each header element html closing tag
onHeadElement | This callback is called to render each header element html opening tag attributes
onHeadElementPrepend | This callback is called after each header element html opening tag
onHeadElementAppend | This callback is called before each header html closing tag
onBodyStart | This callback is called before the body html opening tag 
onBodyStop | This callback is called after the body html closing tag
onBody | This callback is called to render the body html opening tag attributes
onBodyPrepend | This callback is called after the body html opening tag
onBodyAppend | This callback is called before the body html closing tag
onRowStart | This callback is called before each row html opening tag 
onRowStop | This callback is called after each row html closing tag
onRow | This callback is called to render each row html opening tag attributes
onRowPrepend | This callback is called after each row html opening tag
onRowAppend | This callback is called before each row html closing tag
onElementStart | This callback is called before each element html opening tag 
onElementStop | This callback is called after each element html closing tag
onElement | This callback is called to render each element html opening tag attributes
onElementPrepend | This callback is called after each element html opening tag
onElementAppend | This callback is called before each element html closing tag

To register a stepper into the datagrid, you can use the setStepper method:

```php
	// in php file
	$dataGrid = new DataGridContainer();
    $dataGrid->setStepper(new DataGridStepper());
```

An unidirectionnal connection is done between the two class, so, a stepper can only have one DataGrid as parent, and in return, a DataGrid can only have one stepper.

To register a callback, you'll must use the stepper addCallback method. This one take as argument the callback name, the function to use as a closure, the html safe state as optional, and an array of additionnal data.

The name of the callback can be one of the previous callback or any of template callback if you use a personal template. An inexisting callback name does not create error but it will never call.

In this example, we can see that the result of callbacks are naturally escaped, but the third argument allow to display html tags by passing true.
```php
	// in php file
	$dataGrid = new DataGridContainer();
    $dataGrid->setStepper(new DataGridStepper());
    
    // This one display the header before each values
    $dataGrid->getStepper()->addCallback("onElementPrepend", function($type, $process, $row, $data){
        return $data['header']." : ";
    });
    
    // This one display a title before the datagrid
    $dataGrid->getStepper()->addCallback("onGridStart", function($type, $process, $row, $data){
        return "<h3>See our awesome datagrid : </h3>";
    }, true);
    
    // This one set the style of the header at 'color: red'
    $dataGrid->getStepper()->addCallback("onHead", function($type, $process, $row, $data){
        return "style='color: ".$data["color"].";'";
    }, false, array("color"=>"red"));
```

The function registered can take four arguments, given by the stepper. This arguments may be null in function of the place in the template. The first argument is the type of the elements. The second is the total processed data as array, the third will be the current row and the fourth is the array of additional data.

* The type of element is an integer. 0 is an object type and 1 is array.
* The processed data is an array that contain the type as 'type' named index and each rows into integer index.
* The current row is an array that contain the current element as 'primary' named index and each data into integer index.
* The additional data is an array defined on callback registering and where we find in addition the current row index, the current element index, the current header name and the current DataGridStepper, respectively into 'index', 'element', 'header' and 'stepper' named index. Note that if you use this index into the callback definition, they will be override before the callback calling.

Note that the callback must return a string and callbacks have different access to the the variables. They exists but would be null. Refer to the following table to see the access :


Callback name | type | processed | data[row] | data[index] | data[element] | data[header] | data[stepper]
------------- | ---- | --------- | --- | ----- | ------- | ------ | ---------
onGridStart | :white_check_mark: | :white_check_mark: | :x: | :x: | :x: | :x: | :white_check_mark:
onGridStop | :white_check_mark: | :white_check_mark: | :x: | :x: | :x: | :x: | :white_check_mark:
onGrid | :white_check_mark: | :white_check_mark: | :x: | :x: | :x: | :x: | :white_check_mark:
onGridPrepend | :white_check_mark: | :white_check_mark: | :x: | :x: | :x: | :x: | :white_check_mark:
onGridAppend | :white_check_mark: | :white_check_mark: | :x: | :x: | :x: | :x: | :white_check_mark:
onHeadStart | :white_check_mark: | :white_check_mark: | :x: | :x: | :x: | :x: | :white_check_mark:
onHeadStop | :white_check_mark: | :white_check_mark: | :x: | :x: | :x: | :x: | :white_check_mark:
onHead | :white_check_mark: | :white_check_mark: | :x: | :x: | :x: | :x: | :white_check_mark:
onHeadPrepend | :white_check_mark: | :white_check_mark: | :x: | :x: | :x: | :x: | :white_check_mark:
onHeadAppend | :white_check_mark: | :white_check_mark: | :x: | :x: | :x: | :x: | :white_check_mark:
onHeadElementStart | :white_check_mark: | :white_check_mark: | :x: | :x: | :x: | :x: | :white_check_mark:
onHeadElementStop | :white_check_mark: | :white_check_mark: | :x: | :x: | :x: | :x: | :white_check_mark:
onHeadElement | :white_check_mark: | :white_check_mark: | :x: | :x: | :x: | :x: | :white_check_mark:
onHeadElementPrepend | :white_check_mark: | :white_check_mark: | :x: | :x: | :x: | :x: | :white_check_mark:
onHeadElementAppend | :white_check_mark: | :white_check_mark: | :x: | :x: | :x: | :x: | :white_check_mark:
onBodyStart | :white_check_mark: | :white_check_mark: | :x: | :x: | :x: | :x: | :white_check_mark:
onBodyStop | :white_check_mark: | :white_check_mark: | :x: | :x: | :x: | :x: | :white_check_mark:
onBody | :white_check_mark: | :white_check_mark: | :x: | :x: | :x: | :x: | :white_check_mark:
onBodyPrepend | :white_check_mark: | :white_check_mark: | :x: | :x: | :x: | :x: | :white_check_mark:
onBodyAppend | :white_check_mark: | :white_check_mark: | :x: | :x: | :x: | :x: | :white_check_mark:
onRowStart | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :x: | :x: | :white_check_mark:
onRowStop | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :x: | :x: | :white_check_mark:
onRow | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :x: | :x: | :white_check_mark:
onRowPrepend | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :x: | :x: | :white_check_mark:
onRowAppend | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :x: | :x: | :white_check_mark:
onElementStart | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark:
onElementStop | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark:
onElement | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark:
onElementPrepend | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark:
onElementAppend | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark:

Consider to use a service to define the callbacks.

### Create your own template
You can define your own template to display you'r datagrid by configure it into the config.yml symfony file.

```yaml
# in app/config/config.yml
cscfa_data_grid:
	template: AcmeBundle:Default:YourTemplate.html.twig
```

A second choice would be by passing the template by the renderDatagrid twig function :

```twig
{# in your template file #}
{{ renderDatagrid(data, "AcmeBundle:Default:YourTemplate.html.twig") }}
```

##### by extend

You can also extend of the DataGridBundle template. This one is composed with blocks. You will find the following blocks :

```twig
{# in your template file #}
{% extends 'CscfaDataGridBundle:Default:datagrid.html.twig' %}

{% block datagrid %}
    {% block header %}
        {{ parent() }}
    {% endblock %}
    {% block body %}
        {% block row %}
            {{ parent() }}
        {% endblock %}
    {% endblock %}
{% endblock %}
```

Block name | description
---------- | -----------
datagrid | the main datagrid block.
header | the head block.
body | the datagrid block that contain each rows.
row | The row block that contain the row loop.

The variables are defined into the followed blocks :

Variable name | Block name
------------- | -----------
onGridStart | datagrid
onGridStop | datagrid
onGrid | datagrid
onGridPrepend | datagrid
onGridAppend | datagrid 
onHeadStart | header 
onHeadStop | header
onHead | header
onHeadPrepend | header
onHeadAppend | header
onHeadElementStart | header 
onHeadElementStop | header
onHeadElement | header
onHeadElementPrepend | header
onHeadElementAppend | header
onBodyStart | body
onBodyStop | body
onBody | body
onBodyPrepend | body
onBodyAppend | body
onRowStart | row 
onRowStop | row
onRow | row
onRowPrepend | row
onRowAppend | row
onElementStart | row 
onElementStop | row
onElement | row
onElementPrepend | row
onElementAppend | row

##### by yourself

Note, the DataGridContainer is passed to the template into the variable 'data'.

To get data from the DataGridContainer, you'll must use the getData method.
```twig
	{# in your template file #}
    {% set datas = data.getData() %}
```

To get the stepper from the DataGridContainer, you'll must use the getStepper method.
```twig
	{# in your template file #}
    {% set stepper = data.getStepper() %}
```

To render a callback, you must use the datagc function (datagridRenderCallback). This function take three arguments :
* The callback name that will be call from the stepper
* The current row index and element index as formated string
* The stepper instance

The formated string of the index is "i:e" where 'i' is the row index and 'e' the element index. If no one is define at the current template place, the function accept null. If only the row index is define, it can be passed alone.

The headers are accessibles from the getHeader method of the DataGridContainer (passed as 'data' variable).

```twig
	{# in your template file #}
	{{ datagc("onAcmeCallback", null, data.getStepper()) }}
	
	<table>
    {% if data.getHeader() is not empty %}
	    <tr>
        {% for head in data.getHeader() %}
            <th>{{ head }}</th>
        {% endfor %}
	    </tr>
	{% for row in data.getData() %}
		<tr>
		{{ datagc("onAcmeRow", loop.index0, data.getStepper()) }}
		{% set rowIndex = loop.index0 %}
		{% for element in row %}
			<td>
			{{ datagc("onAcmeElement", rowIndex~':'~loop.index0, data.getStepper()) }}
			</td>
		{% endfor %}
		</tr>
	{% endfor %}
	</table>
```

### Use pagination

The 1.1.0 version introduce pagination usage.

The main pagination class must be instanciate into a php context by using DataGridPaginator class.

This class can be instanciate withe three arguments :
* The data to display in an array as first argument
* The integer page to render as second argument
* The integer limit of objects to display as third argument

All of these arguments are optional, the DataGridPaginator class can be instanciate without arguments.

```php
	// In a php context
	$datas = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10)

	/* 
     * Instanciate with arguments
     * 
     * In this example, we instanciate the paginator
     * with a 10 index array, on page 2, with 4 data
     * per page. 
     */
    $paginator = new DataGridPaginator($datas, 2, 4);
```

The paginator allow to be instanciate without arguments, so it purpose some setters to perform it's task.

```php
	//In a php context
    
    /*
     * Note that this example render the
     * same result as the previous example.
     */
    $paginator = new DataGridPaginator();
    $paginator->setPage(2)
    	->setLimit(4)
        ->setData(array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10));
```

To use pagination, the paginator class purpose access to several getter methods, as followed :

Method name | Result
----------- | -------
pageIsset() | Return true if the current requested page exist
getMaxPage() | Return the maximum ammount of page that the current data count and limit allow
getPageData() | Return the current page data

Note that the DataGridPaginator class auto process the data selection when the limit or the data is defined.

The usage of unexisting page, sub zero limit or empty data does not create error.

To use it with the DataGridContainer instance, simply use :

```php
	//In a php context
	$datas = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10)
    $paginator = new DataGridPaginator($datas, 2, 4);
    
    $data = new DataGridContainer($paginator->getPageData());
```

#### Use pagination in twig

The 1.1.0 version introduce pagination usage in a twig context.

To display the paginator page selector, the DataGridBundle purpose the renderPaginator() function. It take the paginator class as first argument.

```twig
	{# in twig template #}
	{{ renderPaginator(pager) }}
```

The paginator, same as DataGridContainer allow to use a DataGridStepper to customize the rendering template. The allowed callbacks are :

Callback name | description
------------- | -----------
onPagerStart | This callback is called before the paginator container opening tag
onPagerStop | This callback is called after the paginator container closing tag
onPager | This callback is called to render the paginator container html opening tag attributes, after the tag name and before the tag end
onPagerPreppend | This callback is called after the paginator container html opening tag
onPagerAppend | This callback is called before the paginator container html closing tag 
onPagerListStart | This callback is called before the paginator list opening tag
onPagerListStop | This callback is called after the paginator list closing tag
onPagerList | This callback is called to render the paginator list html opening tag attributes, after the tag name and before the tag end
onPagerListPreppend | This callback is called after the paginator list html opening tag
onPagerListAppend | This callback is called before the paginator list html closing tag 
onSelectorContainerStart | This callback is called before each paginator element container opening tag
onSelectorContainerStop | This callback is called after each paginator element container closing tag
onSelectorContainer | This callback is called to render each paginator element container html opening tag attributes, after the tag name and before the tag end
onSelectorContainerPreppend | This callback is called after each paginator element container html opening tag
onSelectorContainerAppend | This callback is called before each paginator element container html closing tag 
onSelectorStart | This callback is called before each paginator element opening tag
onSelectorStop | This callback is called after each paginator element closing tag
onSelector | This callback is called to render each paginator element html opening tag attributes, after the tag name and before the tag end
onSelectorPreppend | This callback is called after each paginator element html opening tag
onSelectorAppend | This callback is called before each paginator element html closing tag 
onHref | This callback is called into the link tag's href attribute :bangbang: must passing 'true' on html safe state argument of addCallback function.

Referer to the following table to see callbacks variable access :

Callback name | type | processed | data[row] | data[index] | data[element] | data[header] | data[stepper]
------------- | ---- | --------- | --- | ----- | ------- | ------ | ---------
onPagerStart | :white_check_mark: | :white_check_mark: | :x: | :x: | :x: | :x: | :white_check_mark:
onPagerStop | :white_check_mark: | :white_check_mark: | :x: | :x: | :x: | :x: | :white_check_mark:
onPager | :white_check_mark: | :white_check_mark: | :x: | :x: | :x: | :x: | :white_check_mark:
onPagerPreppend | :white_check_mark: | :white_check_mark: | :x: | :x: | :x: | :x: | :white_check_mark:
onPagerAppend | :white_check_mark: | :white_check_mark: | :x: | :x: | :x: | :x: | :white_check_mark: 
onPagerListStart | :white_check_mark: | :white_check_mark: | :x: | :x: | :x: | :x: | :white_check_mark:
onPagerListStop | :white_check_mark: | :white_check_mark: | :x: | :x: | :x: | :x: | :white_check_mark:
onPagerList | :white_check_mark: | :white_check_mark: | :x: | :x: | :x: | :x: | :white_check_mark:
onPagerListPreppend | :white_check_mark: | :white_check_mark: | :x: | :x: | :x: | :x: | :white_check_mark:
onPagerListAppend | :white_check_mark: | :white_check_mark: | :x: | :x: | :x: | :x: | :white_check_mark:
onSelectorContainerStart | :white_check_mark: | :white_check_mark: | :x: | :white_check_mark: | :x: | :x: | :white_check_mark:
onSelectorContainerStop | :white_check_mark: | :white_check_mark: | :x: | :white_check_mark: | :x: | :x: | :white_check_mark:
onSelectorContainer | :white_check_mark: | :white_check_mark: | :x: | :white_check_mark: | :x: | :x: | :white_check_mark:
onSelectorContainerPreppend | :white_check_mark: | :white_check_mark: | :x: | :white_check_mark: | :x: | :x: | :white_check_mark:
onSelectorContainerAppend | :white_check_mark: | :white_check_mark: | :x: | :white_check_mark: | :x: | :x: | :white_check_mark:
onSelectorStart | :white_check_mark: | :white_check_mark: | :x: | :white_check_mark: | :x: | :x: | :white_check_mark:
onSelectorStop | :white_check_mark: | :white_check_mark: | :x: | :white_check_mark: | :x: | :x: | :white_check_mark:
onSelector | :white_check_mark: | :white_check_mark: | :x: | :white_check_mark: | :x: | :x: | :white_check_mark:
onSelectorPreppend | :white_check_mark: | :white_check_mark: | :x: | :white_check_mark: | :x: | :x: | :white_check_mark:
onSelectorAppend | :white_check_mark: | :white_check_mark: | :x: | :white_check_mark: | :x: | :x: | :white_check_mark:
onHref | :white_check_mark: | :white_check_mark: | :x: | :white_check_mark: | :x: | :x: | :white_check_mark:

The onHref callback have access to a data['page'] and data['limit'] variables.

### Create your own template
You can define your own template to display you'r paginator by configure it into the config.yml symfony file.

```yaml
# in app/config/config.yml
cscfa_data_grid:
	paginator_template: AcmeBundle:Default:YourTemplate.html.twig
```

A second choice would be by passing the template by the renderPaginator twig function :

```twig
{# in your template file #}
{{ renderPaginator(pager, "AcmeBundle:Default:YourTemplate.html.twig") }}
```

##### by extend

You can also extend of the DataGridBundle template. This one is composed with blocks. You will find the following blocks :

```twig
{# in your template file #}
{% extends 'CscfaDataGridBundle:Default:paginatorPageSelector.html.twig' %}

{% block pager %}
    {% block pagedList %}
	    {% block selector %}
	    {% endblock %}
    {% endblock %}
{% endblock %}
```

Block name | description
---------- | -----------
pager | the main paginator block.
pagedList | the page list.
selector | the paginator block that contain each elements.

The variables are defined into the followed blocks :

Variable name | Block name
------------- | -----------
onPagerStart | pager
onPagerStop | pager
onPager | pager
onPagerPreppend | pager
onPagerAppend | pager
onPagerListStart | pagedList
onPagerListStop | pagedList
onPagerList | pagedList
onPagerListPreppend | pagedList
onPagerListAppend | pagedList
onSelectorContainerStart | selector
onSelectorContainerStop | selector
onSelectorContainer | selector
onSelectorContainerPreppend | selector
onSelectorContainerAppend | selector
onSelectorStart | selector
onSelectorStop | selector
onSelector | selector
onSelectorPreppend | selector
onSelectorAppend | selector
onHref | selector

##### by yourself

Note that the paginator instance is passed as 'pager' variable.

The callback definition of the paginator template is the same as the DataGrid template with the 'datagc' function usage.

To display the elements, the simple way is to use a loop :

```twig
	{# in your twig template #}
	
    {% for page in start..end %}
    	{# the element display here #}
    {% endfor %}
```

The 'start' and 'end' variables are defined by the twig extension class to allow the page selection list amount limit.

### Limit the page selection list amount

The renderPaginator() twig function purpose to limit the page amount to display by passing an integer as third arguments. This integer represent an interval, if you define it at 3, a page will be before the current page, and a page will be displayed after the current page.

The default comportment of the function will display an odd number of page and does not display unexisting pages.

```twig
	{# in your twig template #}
	
	{{ renderPaginator(pager, null, 5) }}
```

#### Limit pagination

The pagination limit is setted by the paginator class in a php context, but it possible to purpose a limit selector to the client.

This action is performed by passing an array of allowed limits behind the paginator 'setAllowedLimits(array())' method. This information is used into the template for hydrate the select options tags.

The limit pagination twig extension will display a form to manage the limit choice. This form is created from a Cscfa\Bundle\DataGridBundle\Form\Type\PaginatorLimit type, that contain the current page and limit information, and a limit.

The rendering of the form is perform by the {{ renderPaginatorLimit(pager) }} twig function. This function accept as second argument a template name to override the configuration's defined template.

```twig
	{# in your twig template #}
	
	{{ renderPaginatorLimit(pager) }}
	
	{# or #}
	{{ renderPaginatorLimit(pager, "AcmeBundle:Default:AcmeTemplate.html.twig") }}
```

As the other template, this one use the pager stepper to customize some informations, but many of callbacks must return an array instead of string. To do it, it is necessary to pass 'true' as third argument.

Refer to the list of callbacks :

Callback name | return type | description
------------- | ----------- | -----------
onLimitStart | string | This callback is called before the form opening html tag
onLimitStop | string | This callback is called after the form closing html tag
onLimitFirst | array | This callback is called as form_start() options attributes
onLimitEnd | array | This callback is called as form_end() options attributes
onLimitPrepend | string | This callback is called after the form opening html tag
onLimitAppend | string | This callback is called before the form closing html tag
onSelectLabelStart | string | This callback is called before the select label html tag
onSelectLabelStop | string | This callback is called after the select label html tag
onSelectLabel | array | This callback is called as select form_label() options attributes
onSelectStart | string | This callback is called before the select html tag
onSelectStop | string | This callback is called after the select html tag
onSelect | array | This callback is called as select form_widget() options attributes
onSubmitStart | string | This callback is called before the submit button html tag
onSubmitStop | string | This callback is called after the submit button html tag
onSubmit | array | This callback is called as submit button form_widget() options attributes

To access to the new limit, a controller action must receive the form informations. The route can be defined by the 'inLimitFirst' callback.

```php
//in your controller
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cscfa\Bundle\DataGridBundle\Objects\PaginatorLimitForm;

class AcmeController extends Controller
{
	public function limitAction(Request $request)
	{
		$paginatorLimitForm = new PaginatorLimitForm();
		$paginatorLimitForm->setAllowedLimits(array(5, 10, 25, 50, 100));
		
        $limitForm = $this->createForm("paginatorLimit", $paginatorLimitForm);
        
        if ($request->getMethod() === "POST") {
            $limitForm->handleRequest($request);
            
            $choice = $paginatorLimitForm->getLimit();
            $value = $paginatorLimitForm->getAllowedLimits()[$choice];
            
            $lastLimit = $paginatorLimitForm->getLastLimit();
            $page = $paginatorLimitForm->getPage();
            
        	// render the template
            
        } else {
        	// render the template
        }
	}
}
``` 

### Create your own template
You can define your own template to display you'r paginator limit form by configure it into the config.yml symfony file.

```yaml
# in app/config/config.yml
cscfa_data_grid:
	paginator_limit_template: AcmeBundle:Default:YourTemplate.html.twig
```

A second choice would be by passing the template by the renderPaginatorLimit twig function :

```twig
{# in your template file #}
{{ renderPaginatorLimit(pager, "AcmeBundle:Default:AcmeTemplate.html.twig") }}
```

##### by extend

You can also extend of the DataGridBundle template. This one is composed with blocks. You will find the following blocks :

```twig
{# in your template file #}
{% extends 'CscfaDataGridBundle:Default:paginatorPageSelector.html.twig' %}

{% block limit %}
    {% block select %}
    	{{ parent() }}
    {% endblock %}
    {% block submit %}
    	{{ parent() }}
    {% endblock %}
{% endblock %}
```

Block name | description
---------- | -----------
limit | the main paginator limit block.
select | the select block.
submit | the submit block.

The variables are defined into the followed blocks :

Variable name | Block name
------------- | -----------
onLimitStart | limit
onLimitStop | limit
onLimitFirst | limit
onLimitEnd | limit
onLimitPrepend | limit
onLimitAppend | limit
onSelectLabelStart | select
onSelectLabelStop | select
onSelectLabel | select
onSelectStart | select
onSelectStop | select
onSelect | select
onSubmitStart | submit
onSubmitStop | submit
onSubmit | submit

##### by yourself

Note that the paginator instance is passed as 'pager' variable and the form view as 'form' variable.

The callback definition of the paginator template is the same as the DataGrid template with the 'datagc' function usage.

To display the elements, the simple way is to use the twig form functions :

```twig
	{# in your twig template #}
	
    {{ form_start(form) }}
	    {{ form_row(form.limit) }}
	    {{ form_row(form.submit) }}
    {{ form_end(form) }}
```
