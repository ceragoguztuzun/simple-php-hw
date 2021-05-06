import java.sql.*;  

class Connector
{
    public static void main (String[] args)
    {

        // PART 1 
        // java -cp path/to/mysql-connector-java-8.0.23.jar connector.java
        
        String url = "jdbc:mysql://dijkstra.ug.bcc.bilkent.edu.tr:3306/cerag_oguztuzun";//?useSSL=false&allowPublicKeyRetrieval=true&serverTimezone=UTC";
        String user = "cerag.oguztuzun";
        String password = "7exYR0yV";

        try {
            Class.forName("com.mysql.cj.jdbc.Driver");
            Connection conn = DriverManager.getConnection(url, user, password);
            System.out.println("Connected to DB.");

            Statement stmt = conn.createStatement();
            // remove already existing tables
            stmt.execute("DROP TABLE IF EXISTS apply, company, student");
            
            // define tables
            String applyScheme = "CREATE TABLE apply (sid CHAR(12), cid CHAR(8), PRIMARY KEY(sid,cid), FOREIGN KEY (cid) REFERENCES company(cid), FOREIGN KEY (sid) REFERENCES student(sid))";
            String companyScheme = "CREATE TABLE company (cid CHAR(8), cname VARCHAR(20), quota INT, PRIMARY KEY(cid))";
            String studentScheme = "CREATE TABLE student (sid CHAR(12), sname VARCHAR(50), bdate DATE, address VARCHAR(50), scity VARCHAR(20), year CHAR(20), gpa FLOAT, nationality VARCHAR(20), PRIMARY KEY(sid))";
            
            // add tables to db
            stmt.execute(companyScheme);
            stmt.execute(studentScheme);
            stmt.execute(applyScheme);
                        
            // set records for tables
            String companyRecords = "INSERT INTO company VALUES ('C101', 'microsoft', 2), ('C102', 'merkez bankasi', 5), ('C103', 'tai', 3), ('C104', 'tubitak', 5), ('C105', 'aselsan', 3), ('C106', 'havelsan', 4), ('C107', 'milsoft', 2);";
            String studentRecords = "INSERT INTO student VALUES (21000001, 'John', '1999-05-14', 'Windy', 'Chicago', 'senior', 2.33, 'US'), (21000002, 'Ali', '2001-09-30', 'Nisantasi', 'Istanbul', 'junior', 3.26, 'TC')," +
                                                                "(21000003, 'Veli', '2003-02-25', 'Nisantasi', 'Istanbul', 'freshman', 2.41, 'TC'), (21000004, 'Ayse', '2003-01-15', 'Tunali', 'Ankara', 'freshman', 2.55, 'TC');";
            String applyRecords = "INSERT INTO apply VALUES (21000001, 'C101'), (21000001, 'C102'), (21000001, 'C103'), (21000002, 'C101'), (21000002, 'C105'), (21000003, 'C104'), (21000003, 'C105'), (21000004, 'C107');";
            
            // add records to tables
            stmt.execute(companyRecords);
            stmt.execute(studentRecords);
            stmt.execute(applyRecords);
            
            // display student table
            String query = "SELECT * FROM student";
            ResultSet rs = stmt.executeQuery(query);
            
            String sid, sname, bdate, address, scity, year, gpa, nationality;

            System.out.printf("%70s\n","---STUDENT---");
            System.out.printf("%s %20s %15s %15s %15s %15s %15s %15s \n", "sid", "sname", "bdate", "address", "scity", "year", "gpa", "nationality");
            System.out.printf("------------------------------------------------------------------------------------------------------------------------\n");
            while (rs.next()) {
                sid = rs.getString("sid");
                sname =rs.getString("sname");
                bdate = rs.getString("bdate");
                address = rs.getString("address");
                scity = rs.getString("scity");
                year =rs.getString("year");
                gpa =rs.getString("gpa");
                nationality =rs.getString("nationality");
                System.out.printf("%s %15s %15s %15s %15s %15s %15s %15s \n", sid, sname, bdate, address, scity, year, gpa, nationality);  
            }

            // close connection and statement
            stmt.close();
            conn.close();

        } catch (SQLException | ClassNotFoundException e) {
            System.err.println("SQL Exception catched.");
            e.printStackTrace();
        }
    
    }
}

