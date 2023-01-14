<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Description</th>
    </tr>
    </thead>
    <tbody>
      @foreach($departments as $department)
       <tr>
           <td>{{ $department->name }}</td>
           <td>{{ $department->email }}</td>
       </tr>
   @endforeach

    </tbody>
</table>
