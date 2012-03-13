function renderMateriasTable() {
    // traigo la id del plan seleccionado
    var planId =  $("#plan-select").val();    
    // cargo unos params para crear el ajax request
    var url = "/ajax/get-materias-plan-json";
    var params = "planId=" + planId;
       
    $.ajax({
        data: params,
        type: "POST",
        dataType: "json",
        url: url,
        success: function(jsonData) {
            if (!jsonData) {
                $('#materias-tbody').html("");
                return;
            }
            var tableHtml = '';
            var count = 0;
	    
            $.each(jsonData, function() {
                count += 1;
                var trClass = (count % 2 == 0) ? "even" : "odd";
		
                tableHtml += '<tr class="' + trClass + ' gradeA">';
                tableHtml += '<td class="center"> ' + this.nombre + ' </td>';
                tableHtml += '<td class="center"><input type="checkbox" name="selectedMaterias[]" value="' + this.id + '" /></td>';
                tableHtml += '</tr>';        
            });
	    
            $('#materias-tbody').html(tableHtml);
        }
    });
}

function renderPeriodosSelect(planId) {
    if (!planId) {
        return;
    }
    // cargo unos params para crear el ajax request
    var url = "/ajax/get-periodos-plan-json";
    var params = "planId=" + planId;
       
    $.ajax({
        data: params,
        type: "GET",
        dataType: "json",
        url: url,
        //esta llamada, y la proxima (la de los cursos) TIENEN que ser asincronicas,
        //sino estamos haciendo dos consultas a la vez, y una, la segunda (la de los cursos)
        //es sobre info que todavia no esta cargada. la verdad toda una genialidad de jeremias darse cuenta de esto
        async: false,
        success: function(jsonData) {
            if (!jsonData) {
                $('#periodo-select').html("");
                $("#curso-select").html("");
                return;
            }    
            var selectHtml = '';
            
            $.each(jsonData, function() {
                selectHtml += '<option value="' + this.id + '">' + this.numero + '</option>';
            });
    
            $('#periodo-select').html(selectHtml);
        }
    });
    
    //despues que cargo los periodos, si cargue alguno, voy a cargar el select de las materias
    if (!$("#periodo-select").val()) {
        return;
    } else {
        $("#periodo-select, option[value='0']").attr('selected','selected');
        $("#periodo-select").trigger('change');
    }
        
}

function renderCursosSelect(planId, periodoId) {
    if (!planId || !periodoId) {
        return;
    }
    // cargo unos params para crear el ajax request
    var url = "/ajax/get-cursos-periodo-json";
    var params = "planId=" + planId +"&periodoId=" + periodoId;
       
    $.ajax({
        data: params,
        type: "GET",
        dataType: "json",
        url: url,
        async: false,
        success: function(jsonData) {
            if (!jsonData) {
                $('#curso-select').html("");
                return;
            }    
            var selectHtml = '';
            
            $.each(jsonData, function() {
                selectHtml += '<option value="' + this.id + '">' + this.mat_nombre + '-' + this.anio + '</option>';
            });
    
            $('#curso-select').html(selectHtml);
        }
    });
    
    if (!$("#curso-select").val()) {
        return;
    } else {
        $("#curso-select, option[value='0']").attr('selected','selected');
    }
}

function updateAlumnosPlanTable(planId, cursoId, oAlumnosTable) {
    if (!planId || !cursoId || !oAlumnosTable) {
        return;
    }
    
    //loads data
    var url = "/ajax/get-alumnos-plan-json";
    var params = "planId=" + planId + "&cursoId=" + cursoId;
    
    $.ajax({
        data: params,
        type: "GET",
        dataType: "json",
        url: url,
        success: function(jsonData) {
            oAlumnosTable.fnClearTable();
            
            $.each(jsonData, function() {
                /*
                oAlumnosTable.fnAddData([
                    this.ci,
                    this.nombre,
                    this.apellido,
                    '<input type="hidden" name="alumnos[]" value="' + this.id + '" />'
                    ]);
                */
            });
            
            oAlumnosTable.fnDraw();
        }
    });
}

function updateAlumnosEventoCalTable(cursoId, oAlumnosTable) {
    if (!cursoId || !oAlumnosTable) {
        return;
    }
    // cargo unos params para crear el ajax request
    var url = "/ajax/get-alumnos-curso-json";
    var params = "cursoId=" + cursoId + "&examen=1";
       
    $.ajax({
        data: params,
        type: "POST",
        dataType: "json",
        url: url,
        success: function(jsonData) {
            oAlumnosTable.fnClearTable();
            $.each(jsonData, function() {
                oAlumnosTable.fnAddData([
                    this.ci,
                    this.nombre,
                    this.apellido,
                    '<input type="text" name="observaciones[]" />',
                    '<input class="span1" id="nota" name="nota[]" maxlength="3" type="text"/>',
                    '<input type="hidden" name="allAlumnos[]" value="' + this.id + '" />'
                    ]);
            });
            oAlumnosTable.fnDraw();
        }
    });
}