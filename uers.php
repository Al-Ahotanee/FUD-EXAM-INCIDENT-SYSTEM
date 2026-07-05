<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal - FUD IMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script crossorigin src="https://unpkg.com/react@18/umd/react.development.js"></script>
    <script crossorigin src="https://unpkg.com/react-dom@18/umd/react-dom.development.js"></script>
    <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-900">
<div id="root"></div>

<script type="text/babel">
    const API = './api.php';
    const { useState, useEffect } = React;

    function UserApp() {
        const [user, setUser] = useState(null);
        const [view, setView] = useState('reports'); // reports, new_report
        const [incidents, setIncidents] = useState([]);
        
        useEffect(() => {
            axios.post(`${API}?action=check_auth`).then(res => {
                if (res.data.isAuthenticated && ['invigilator', 'hod', 'dean'].includes(res.data.user.role)) {
                    setUser(res.data.user); fetchIncidents();
                } else window.location.href = 'index.php';
            });
        }, []);

        const fetchIncidents = () => axios.post(`${API}?action=get_incidents`).then(res => setIncidents(res.data));
        const logout = () => { axios.post(`${API}?action=logout`).then(() => window.location.href = 'index.php'); };

        const ReportForm = () => {
            const [msg, setMsg] = useState('');
            const submit = async (e) => {
                e.preventDefault();
                const fd = new FormData(e.target);
                fd.append('action', 'report_incident');
                await axios.post(API, fd, { headers: {'Content-Type': 'multipart/form-data'} });
                setMsg('Report submitted successfully.');
                e.target.reset(); fetchIncidents();
            };
            return (
                <div className="bg-white p-8 rounded-xl shadow-sm border border-gray-100 max-w-2xl">
                    <h2 className="text-2xl font-bold mb-6">Log New Incident</h2>
                    {msg && <div className="bg-green-100 text-green-700 p-4 rounded mb-6 font-bold">{msg}</div>}
                    <form onSubmit={submit} className="space-y-4">
                        <div className="grid grid-cols-2 gap-4">
                            <div><label className="block text-sm font-bold text-gray-700 mb-1">Student Name</label><input name="student_name" className="w-full border p-2 rounded" required/></div>
                            <div><label className="block text-sm font-bold text-gray-700 mb-1">Matric Number</label><input name="student_matric" className="w-full border p-2 rounded" required/></div>
                        </div>
                        <div><label className="block text-sm font-bold text-gray-700 mb-1">Description</label><textarea name="description" className="w-full border p-2 rounded h-32" required></textarea></div>
                        <div><label className="block text-sm font-bold text-gray-700 mb-1">Evidence (Optional)</label><input type="file" name="evidence" className="w-full border p-2 rounded bg-gray-50"/></div>
                        <button className="bg-green-600 text-white font-bold py-3 px-6 rounded hover:bg-green-700">Submit Report</button>
                    </form>
                </div>
            );
        };

        if (!user) return null;

        return (
            <div className="flex h-screen overflow-hidden">
                <aside className="w-64 bg-slate-800 text-white flex flex-col">
                    <div className="p-6 font-bold text-xl border-b border-white/10 uppercase">{user.role} Portal</div>
                    <nav className="flex-1 p-4 space-y-2">
                        {user.role === 'invigilator' && <button onClick={()=>setView('new_report')} className={`w-full text-left px-4 py-3 rounded-lg flex items-center gap-3 transition ${view==='new_report'?'bg-blue-600 text-white':'text-gray-400 hover:bg-white/5'}`}><i className="fas fa-plus"></i> New Report</button>}
                        <button onClick={()=>setView('reports')} className={`w-full text-left px-4 py-3 rounded-lg flex items-center gap-3 transition ${view==='reports'?'bg-blue-600 text-white':'text-gray-400 hover:bg-white/5'}`}><i className="fas fa-list"></i> View Records</button>
                    </nav>
                    <div className="p-4 border-t border-white/10"><button onClick={logout} className="w-full text-left px-4 py-2 bg-red-500 rounded text-white hover:bg-red-600"><i className="fas fa-sign-out-alt mr-2"></i>Logout</button></div>
                </aside>
                <main className="flex-1 overflow-y-auto p-8">
                    {view === 'new_report' ? <ReportForm/> : 
                    <div>
                        <h2 className="text-2xl font-bold mb-6">Incident History</h2>
                        <div className="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <table className="w-full text-left">
                                <thead className="bg-gray-50 uppercase text-xs text-gray-500">
                                    <tr><th className="p-4">Date</th><th className="p-4">Student</th><th className="p-4">Status</th></tr>
                                </thead>
                                <tbody className="divide-y">
                                    {incidents.length === 0 ? <tr><td colSpan="3" className="p-4 text-center text-gray-500">No records found.</td></tr> :
                                     incidents.map(inc => (
                                        <tr key={inc.id}>
                                            <td className="p-4 text-sm text-gray-500">{new Date(inc.created_at).toLocaleDateString()}</td>
                                            <td className="p-4 font-bold">{inc.student_name} <span className="text-xs font-normal text-gray-400">({inc.student_matric})</span></td>
                                            <td className="p-4"><span className="bg-gray-100 px-2 py-1 rounded text-xs font-bold text-gray-600">{inc.status}</span></td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>}
                </main>
            </div>
        );
    }

    const root = ReactDOM.createRoot(document.getElementById('root'));
    root.render(<UserApp />);
</script>
</body>
</html>