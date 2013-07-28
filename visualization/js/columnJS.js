google.load('visualization', '1.0', {'packages':['corechart']});
//google.setOnLoadCallback(drawColumn);

var columndata;

function loadColumns() {
	columns = document.getElementsByName("columnDivs");
	gadgetIDs = new Array();
    for(columnNum=0 ; columnNum<columns.length ; columnNum++){
        theColumn = columns[columnNum];
		gadgetIDs.push(theColumn.id);
        drawColumn(2,gadgetIDs[columnNum]);
    }
}

/****************
type
1: initial creation of column chart
2: reload column chart from database
3: edit existing column chart
*****************/
/*function drawColumn(type, vid){
	gadgetID = vid;
	titleNo = $('#titleNo').val();
    where = $("#where").val();	
		
	if(type == 1) { //initial creation of column chart
		createNewColumn();
		columnCat = $('#columnCat').val();
		columnAgg = $('#columnAgg').val();
		columnAggType = $('input:radio[name="columnAggType"]:checked').val();
		settings = columnCat + ";" + columnAgg + ";" + columnAggType + ";";
		$('#setting' + gadgetID).val(settings);	
	}
	else if(type == 2) { //reload column chart from database
		settings = $('#setting' + gadgetID).val();
		var n = settings.split(";");
		columnCat = n[0];
		columnAgg = n[1];
		columnAggType = n[2];
	}
	else if(type == 3) { //edit existing column chart
		columnCat = $('#columnCatEdit').val();
		columnAgg = $('#columnAggEdit').val();
		columnAggType = $('input:radio[name="columnAggTypeEdit"]:checked').val();
		settings = columnCat + ";" + columnAgg + ";" + columnAggType + ";";
		$('#setting' + gadgetID).val(settings);	
	}

	$.ajax({
		type: 'POST',
		url: "getColumn.php",
		data: {'columnCat':columnCat,'columnAgg':columnAgg,'columnAggType':columnAggType,'titleNo':titleNo, 'where':where},
		success: function(JSON_Response){
			var JSONResponse = JSON_Response;
			data = new google.visualization.DataTable();
			data.addColumn('string', columnCat);
			data.addColumn('number',columnAggType);
			
			for(i=0 ; JSONResponse[i]!=null ; i++){
				data.addRow();
				data.setCell(i,0,String(JSONResponse[i]["Category"]));
				data.setCell(i,1,parseFloat(String(JSONResponse[i]["AggValue"])));
			}
			columndata = data;
			//alert(gadgetID);
			$("#columnResult" + gadgetID).height($("#" + gadgetID).height() - $(".gadget-header").height() - 20);			
			generateColumn(gadgetID);
		},
		dataType: 'json',
		async:false
	});
    //$("#" + gadgetID).resize(function() {
	$("div[name='columnDivs']").resize(function() {
		tempid = $(this).attr("id");
        $("#columnResult" + tempid).height($("#" + tempid).height() - $(".gadget-header").height() - 20);
        generateColumn(tempid);
    });		
	
	
	if(type == 1) {
		$('#addColumn').modal('hide');
	}
	else if(type ==3) {
		$('#editColumn').modal('hide');
	}
}*/

/*function generateColumn(a) {
	var options = {
		title: 'Column Chart for ' + columnAgg + ' ' + columnAggType + ' based on ' + columnCat,
		//hAxis: {title: 'Location', titleTextStyle: {color: 'red'}},
		width: "100%",
		height:"90%"
	};
	var chart = new google.visualization.ColumnChart(document.getElementById('columnResult'+a));
	chart.draw(columndata,options);
}*/
$(document).ready(function (){
	$('#editColumn').on('hidden', function () {
		clearColumnEditForm();
		})
	})
function columnFormToDatainfo() {
	var sid = $("#addColumnSid").val();
	var sname = $('#addColumnSid').find("option:selected").text();
	var where;
	var table = $("#addColumnTable").val();
	var columnCat = $('#columnCat').val();
	var columnAgg = $('#columnAgg').val();
	var columnAggType = $('input:radio[name="columnAggType"]:checked').val();
	return new ColumnDatainfo(columnCat,columnAgg,columnAggType,sid,sname,table,where);
}
function editColumnFormToDatainfo() {
	var sid = $("#editColumnSid").val(); 
	var sname = $('#editColumnSid').find("option:selected").text();
	var table = $("#editColumnTable").val();
	var where;	
	var columnCat = $('#columnCatEdit').val();
	var columnAgg = $('#columnAggEdit').val();
	var columnAggType = $('input:radio[name="columnAggTypeEdit"]:checked').val();
	return new ColumnDatainfo(columnCat,columnAgg,columnAggType,sid,name,table,where);
}
function ColumnDatainfo(columnCat,columnAgg,columnAggType,sid,sname,table,where) {
	this.columnCat = columnCat;
	this.sname = sname;
	this.columnAgg = columnAgg;
	this.columnAggType = columnAggType;
	this.sid = sid;
	this.table = table;
	this.where = where;
}
function columnDataInfoToForm(columnDatainfo) {
	var sid = columnDatainfo.sid;
	var sname = columnDatainfo.sname;
	var where = columnDatainfo.where;
	var table = columnDatainfo.table;
	var columnCat = columnDatainfo.columnCat;
	var columnAgg = columnDatainfo.columnAgg;
	var columnAggType = columnDatainfo.columnAggType;
	$('#editColumnSid').val(sid);
	$('#editColumnSid').find("option:selected").text(sname);
	$('#editColumnTable').val(table);
	$('#editColumnTable').change();
	$('#columnCatEdit').val(columnCat);
	$('#columnAggEdit').val(columnAgg);
	$('input:radio[name="columnAggTypeEdit"][value="'+columnAggType+'"]').attr('checked',true);
}
function clearColumnEditForm() {
	$('#columnCatEdit').val(1);
	$('#columnAggEdit').val(1);
	$('input:checkbox[name="columnAggTypeEdit"]').each(function() {
	    $(this).removeAttr('checked');
	})
}
function createNewColumnGadget() {
	var d = new Date();
	var ranNum = 1 + Math.floor(Math.random() * 100);
	var gadgetID = d.getTime() + ranNum + "";
	
	var gadget = "<div name='columnDivs' id='"+gadgetID+"' class='gadget' style='top: 50px; left:0px; width:500px; height:400px' type='column'>";
	gadget += "<div class='gadget-header'><div class='gadget-title'>column chart " + gadgetID+"</div>";
	gadget += "<div class='gadget-close'><i class='icon-remove'></i></div>";
	gadget += "<div class='gadget-edit edit-column edit-new-column'><i class='icon-edit'></i></div></div>";
	gadget += "<input type='hidden' id='setting"+gadgetID+"' value='' />";
	gadget += "<div class='gadget-content'>";
	gadget += "<div id='columnResult"+gadgetID+"' style='width:100%'></div>";
	gadget += "</div></div>";

	$("#columnResult"+gadgetID).height($("#"+gadgetID).height() - $(".gadget-header").height() - 20);
	
	$('.chart-area').append(gadget);
	$( ".gadget" )
		.draggable({ handle: ".gadget-header" })
		.resizable();
	
	$(".gadget-close").click(function() {	
		$(this).parent().parent().remove();
	})
	$('#'+gadgetID+' .edit-column').click(function(){
		var editGadgetID = $(this).parent().parent().attr('id');
		var cid = $("#"+editGadgetID+" .chartID").val();
		resetEditFormSidTable("editColumnSid",'editColumnTable');
		columnDataInfoToForm(CHARTS[cid]['datainfo']);
		$('#editColumn').modal('show');
		CANVAS.selectedChart = cid;
       });
	$("div[name='columnDivs']").resize(function() {
		var cid = $(this).find('.chartID').val();
		var gadgetID = $(this).attr('id');
		var chart = CHARTS[cid];
		$("#columnResult" + gadgetID).height($("#" + gadgetID).height() - $(".gadget-header").height() - 20);
		refreshColumn(chart.chartData,chart.queryResult,"columnResult"+gadgetID);
	})
	return gadgetID;
	/*
	$(".edit-new-column").click(function() {
		var newColumnDiv = $(this).parent().parent();
		$(this).parent().parent().find(".edit-column").trigger("click");
	})*/
}
function drawColumn(souceData,gadgetID) {
	google.load("visualization", "1", {packages:["corechart"]});
	var columnCat = souceData['columnCat'];
	var columnAggType = souceData['columnAggType'];
	var data = new google.visualization.DataTable();
	data.addColumn('string', columnCat);
	data.addColumn('number',columnAggType);		
	for(var i=0 ; souceData['content'][i]!=null ; i++){
		data.addRow();
		data.setCell(i,0,String(souceData['content'][i]["Category"]));
		data.setCell(i,1,parseFloat(String(souceData['content'][i]["AggValue"])));
	}
	//var data = new google.visualization.arrayToDataTable(souceData['content']);
	var options = {
		title: 'Column Chart',
		//hAxis: {title: 'Location', titleTextStyle: {color: 'red'}},
		width: "100%",
		height:"90%"
	};
	var chart = new google.visualization.ColumnChart(document.getElementById(gadgetID));
	chart.draw(data,options);
	return data;
}
function refreshColumn(data,soucreData,gadgetID) {
	var options = {
		title: 'Column Chart',
		//hAxis: {title: 'Location', titleTextStyle: {color: 'red'}},
		width: "100%",
		height:"90%"
	};
	var chart = new google.visualization.ColumnChart(document.getElementById(gadgetID));
	chart.draw(data,options);
}
function addColumnChart() {
	var datainfo = columnFormToDatainfo();
	var gadgetID = createNewColumnGadget();
	$.ajax({
		type: 'POST',
		url: "control.php",
		data: {action: 'addChart',
		name: 'ColumnChart',
		vid: $('#vid').val(),
		type: 'column',
		width: 1200,
		height: 600,
		depth: ++maxDepth,
		top: 50,
		left: 0,
		note: 'dfdff',
		datainfo: datainfo},
		success: function(JSON_Response){
			JSON_Response = jQuery.parseJSON(JSON_Response);
			var queryResult = JSON_Response['queryResult'];
			CHARTS[JSON_Response['cid']] = new Chart(JSON_Response['cid'],JSON_Response['name'],JSON_Response['type'],JSON_Response['top'],JSON_Response['left'],JSON_Response['height'],JSON_Response['width'],JSON_Response['depth'],JSON_Response['note'],JSON_Response['datainfo'],JSON_Response['queryResult'],"columnResult" + gadgetID)
			$("#columnResult" + gadgetID).height($("#" + gadgetID).height() - $(".gadget-header").height() - 20);
			CHARTS[JSON_Response['cid']].chartData = drawColumn(queryResult,'columnResult'+gadgetID);
			$("#"+gadgetID).find('.gadget-title').text("Column chart in "+CHARTS[JSON_Response['cid']].getSname() + ":" + CHARTS[JSON_Response['cid']].getTable());
			gadgetProcess(gadgetID,JSON_Response['cid'],JSON_Response['name'],JSON_Response['top'],JSON_Response['left'],JSON_Response['height'],JSON_Response['width'],JSON_Response['depth'],JSON_Response['type'],JSON_Response['note'],'datainfo');
			$('#addColumn').modal('hide');
		}
		})
}
//Load existing chart Column chart
function loadColumnChart(sourceData) {
	var gadgetID = createNewColumnGadget();
	var queryResult = sourceData['queryResult'];
	drawColumn(queryResult,'columnResult'+gadgetID);
	gadgetProcess(gadgetID,sourceData['cid'],sourceData['name'],sourceData['top'],sourceData['left'],sourceData['height'],sourceData['width'],sourceData['depth'],sourceData['type'],sourceData['note'],'datainfo');
	CHARTS[sourceData['cid']] = new Chart(sourceData['cid'],sourceData['name'],sourceData['type'],sourceData['top'],sourceData['left'],sourceData['height'],sourceData['width'],sourceData['depth'],sourceData['note'],sourceData['datainfo'],sourceData['queryResult'],"columnResult" + gadgetID)
	$("#columnResult" + gadgetID).height($("#" + gadgetID).height() - $(".gadget-header").height() - 20);
	CHARTS[sourceData['cid']].chartData = drawColumn(queryResult,'columnResult'+gadgetID);
	$("#"+gadgetID).find('.gadget-title').text("Column chart in "+CHARTS[sourceData['cid']].getSname() + ":" + CHARTS[sourceData['cid']].getTable());

}
//update the chart
function updateColumnResult(cid) {
	var chart = CHARTS[cid];
	var gadgetID = CHARTS[cid].gadgetID;
	var datainfo = editColumnFormToDatainfo();
	$.ajax({
		type: 'POST',
		url: 'control.php',
		data: {
			vid: CANVAS.vid,
			action: 'updateChartResult',
			cid: cid,
			datainfo: datainfo,
			},
		success: function(JSON_Response) {
			JSON_Response = jQuery.parseJSON(JSON_Response);
			var queryResult = JSON_Response['queryResult'];
			CHARTS[cid].datainfo = datainfo;
			CHARTS[cid].queryResult = queryResult;
			drawColumn(queryResult,gadgetID);
			$('#editColumn').modal('hide');
		}
		})
}