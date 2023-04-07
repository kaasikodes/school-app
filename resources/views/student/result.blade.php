<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
		table {
			border-collapse: collapse;
			width: 100%;
			margin-bottom: 20px;
			border: 1px solid #ddd;
			font-size: 10px;
			text-align: left;
		}
		th, td {
			padding: 8px;
			border: 1px solid #ddd;
		}
		th {
			background-color: #f2f2f2;
			font-weight: bold;
		}
		tr:nth-child(even) {
			background-color: #f2f2f2;
		}
        header{
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            background: red;
        }
	</style>
</head>
<body>
    <p>{{ $description }}</p>
    <img alt='test' src='https://cdn.dribbble.com/users/1615584/avatars/normal/657733f5cf034c8778c3e07d4c9e4c4e.jpg?1488390406&compress=1&resize=80x80'/>

    <br>
    <div>
        <header>
            <img src="https://ui-avatars.com/api/?name=C+E+I&color=7F9CF5&background=EBF4FF" alt=""/>
            <div>
                <h3>{{$school->name}}</h3>
            </div>
            <img src="https://ui-avatars.com/api/?name=C+E+I&color=7F9CF5&background=EBF4FF" alt=""/>
        </header>

        <div>
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