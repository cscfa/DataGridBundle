# DataGrid bundle documentation
### Version: 1.0.0

The DataGrid bundle allow to display a datagrid into twig template.

##### Installation

Register the bundle into app/appKernel.php

```
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

```
// in php file
use Cscfa\Bundle\DataGridBundle\Objects\DataGridContainer;
```

The datagrid system use the DataGridContainer class to define the datagrid informations.

Basically, this class is instanciate with a set of data to display, the access method to get the specifically data from each elements, the headers to display and the elements type.

By 'element', we understand the row data container. This element can be an array or an object. By default, the container use each element as array. If you give an array of object, as a doctrine findAll result, you will must specify it by passing DataGridContainer::TYPE_OBJECT as fourth argument of the constructor.

To display data, you'll must specify the access methods to it. By passing an array of string you can inform on the access method of each elements data. If the elements are array, the access method will be an array of key to display. If the elements are objects, the access method will be the getter methods of the objects.

The header argument is an array of string that inform on the header of each column.

```
	// Asume this code is into a controller
	
	$datas = array(array("element 1.1", "element 1.2"), array("element 2.1", "element 2.2"));
	
	$dataGrid = new DataGridContainer($datas, array(0, 1), array("head1", "head2"), DataGridContainer::TYPE_ARRAY);
	
	$this->render("AcmeBundle:Default:index.html.twig", array("data"=>$dataGrid));
```

And into the twig template :

```
	{# in your template file #}
	{{ renderDatagrid(data) }}
```

No one of the arguments are required to instanciate the DataGridContainer class and empty container does not generate exception.

You can instanciate a datagrid with this code : 

```
	// Asume this code is into a controller
	$dataGrid = new DataGridContainer();
	
	$this->render("AcmeBundle:Default:index.html.twig", array("data"=>$dataGrid));
```

And define each arguments with this :

```
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

```
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

```
	// in php file
	$dataGrid = new DataGridContainer();
    $dataGrid->setStepper(new DataGridStepper());
```

An unidirectionnal connection is done between the two class, so, a stepper can only have one DataGrid as parent, and in return, a DataGrid can only have one stepper.

To register a callback, you'll must use the stepper addCallback method. This one take as argument the callback name, the function to use as a closure, the html safe state as optional, and an array of additionnal data.

The name of the callback can be one of the previous callback or any of template callback if you use a personal template. An inexisting callback name does not create error but it will never call.

In this example, we can see that the result of callbacks are naturally escaped, but the third argument allow to display html tags by passing true.
```
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

```
# in app/config/config.yml
cscfa_data_grid:
	template: AcmeBundle:Default:YourTemplate.html.twig
```

A second choice would be by passing the template by the renderDatagrid twig function :

```
{# in your template file #}
{{ renderDatagrid(data, "AcmeBundle:Default:YourTemplate.html.twig") }}
```

##### by extend

You can also extend of the DataGridBundle template. This one is composed with blocks. You will find the following blocks :

```
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

The extention archetype of the template can be schematize as :

```
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
```
	{# in your template file #}
    {% set datas = data.getData() %}
```

To get the stepper from the DataGridContainer, you'll must use the getStepper method.
```
	{# in your template file #}
    {% set stepper = data.getStepper() %}
```

To render a callback, you must use the datagc function (datagridRenderCallback). This function take three arguments :
* The callback name that will be call from the stepper
* The current row index and element index as formated string
* The stepper instance

The formated string of the index is "i:e" where 'i' is the row index and 'e' the element index. If no one is define at the current template place, the function accept null. If only the row index is define, it can be passed alone.

The headers are accessibles from the getHeader method of the DataGridContainer (passed as 'data' variable).

```
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
