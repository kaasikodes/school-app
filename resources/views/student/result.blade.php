<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
		.result table {
			border-collapse: collapse;
			width: 100%;
			margin-bottom: 20px;
			border: 1px solid #ddd;
			font-size: 10px;
			text-align: left;
		}
		.result th, .result td {
			padding: 8px;
			border: 1px solid #ddd;
		}
		.result th {
			background-color: #f2f2f2;
			font-weight: bold;
		}
		.result tr:nth-child(even) {
			background-color: #f2f2f2;
		}
        .header, .student-info {
           
            margin-bottom: 30px;
        }
        .header table {
           
            width: 100%;
        }
        .header table h3 {
           
            font-size: 30px;
            font-weight: 400;
            margin: 5px;
            text-transform: uppercase;
        }
        .header table h6 {
           
            font-size: 18px;
            font-weight: 400;
            margin: 0px;
            text-transform: uppercase;  
        }
      
        .student-info table{
            width: 100%;
            font-size: 12px;


        }
        .student-info table th {
            text-align: left;
            text-transform: capitalize;  




        }
	</style>
</head>
<body>
    
    <div>
        <div class='header'>
            <table>
            <thead>
            <tr>
            <th>
            <img src="https://ui-avatars.com/api/?name=C+E+I&color=7F9CF5&background=EBF4FF" alt=""/>

            </th>
            <th>
            <div>
                <h3>{{$school->name}}</h3>
                <h6>{{$title}}</h6>
            </div>
            </th>
            <th>
                <img src="https://ui-avatars.com/api/?name=C+E+I&color=7F9CF5&background=EBF4FF" alt=""/>

            </th>




            </tr>                            
            </thead>
            </table>
        </div>

        <div class="student-info">
            
           
            <table>
                <tbody>
                    <tr>
                        <td>
                            <table>
                                <tbody>
                                    @foreach($leftItems as $key => $value)
                                    <tr>
                                        <th>
                                            {{$key}}:
                                        </th>
                                        <td>
                                            {{$value}}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                        <td>
                            <table>
                                <tbody>
                                    @foreach($rightItems as $key => $value)
                                    <tr>
                                        <th>
                                            {{$key}}:
                                        </th>
                                        <td>
                                            {{$value}}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                       
                      
                    </tr>
                </tbody>
            </table>


        </div>
        

        <div class = 'result'>
            <table>
            <thead>
                <tr>
                <th>#</th>
                <th>Course</th>
                @foreach (json_decode($setting->assessment_template->break_down) as $key => $item)
                <th >{{$item->name}}({{$item->value}})</th>


                @endforeach
            

                <th>Total</th>
                <th>Highest in Class</th>
                <th>Lowest in Class</th>
                <th>Class Average</th>
                <th>Position</th>
                <th>Out of</th>
                <th>Grade</th>
        
                </tr>                            
            </thead>
            <tbody>
                @foreach ($courses as $key => $course)

                    <tr>
                        <td>{{$key + 1 }}</td>
                        <td>{{ $course->course->name }}</td>
                        @foreach (json_decode($setting->assessment_template->break_down) as $key => $item)
                        <td >{{((array) json_decode($course->break_down))[$item->name]}}</td>


                        @endforeach
                
                        <td>{{ $course->total}}</td>

                        <td>{{ $course->session_level_course_stats['highestScore']}}</td>
                        <td>{{ $course->session_level_course_stats['lowestScore']}}</td>
                        <td>{{ $course->session_level_course_stats['classAverage']}}</td>
                        <td>{{ $course->session_level_course_stats['position']}}</td>
                        <td>{{ $course->session_level_course_stats['totalStudents']}}</td>
                        <td>{{ $course->grade}}</td>
                
                    </tr>

                @endforeach
                
            
            </tbody>
            </table>
        </div>
            
    </div>


    <p style="text-align: center;">{!! $footer !!}</p>
</body>
</html>