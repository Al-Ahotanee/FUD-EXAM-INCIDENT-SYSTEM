<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FUD OIRMF - Welcome</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- React & Babel -->
    <script crossorigin src="https://unpkg.com/react@18/umd/react.development.js"></script>
    <script crossorigin src="https://unpkg.com/react-dom@18/umd/react-dom.development.js"></script>
    <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1B3A6B',
                        accent: '#2E6DB4',
                        dark: '#0f172a'
                    },
                    animation: {
                        'fade-in': 'fadeIn 1s ease-out',
                        'slide-up': 'slideUp 0.8s ease-out forwards',
                    },
                    keyframes: {
                        fadeIn: { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
                        slideUp: { '0%': { opacity: '0', transform: 'translateY(20px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } }
                    }
                }
            }
        }
    </script>
    <style>
        html { scroll-behavior: smooth; }
        .glass-nav { background: rgba(27, 58, 107, 0.85); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 font-sans overflow-x-hidden selection:bg-accent selection:text-white">
<div id="root"></div>

<script type="text/babel">
    const API = './api.php';
    const { useState, useEffect } = React;

    function App() {
        const [view, setView] = useState('landing'); 
        const [csrf, setCsrf] = useState('');
        
        useEffect(() => {
            axios.post(`${API}?action=check_auth`).then(res => {
                setCsrf(res.data.csrf_token);
                if (res.data.isAuthenticated) redirectUser(res.data.user.role);
            });
        }, []);

        const redirectUser = (role) => {
            if (role === 'admin') window.location.href = 'admin.php';
            else if (role === 'exam_officer') window.location.href = 'eo.php';
            else window.location.href = 'users.php';
        };

        const apiCall = async (action, data = {}) => {
            return axios.post(`${API}?action=${action}`, data, {
                headers: { 'X-Csrf-Token': csrf }
            });
        };

        // --- LANDING PAGE COMPONENTS ---
        
        const Navbar = () => {
            const [scrolled, setScrolled] = useState(false);
            useEffect(() => {
                const handleScroll = () => setScrolled(window.scrollY > 50);
                window.addEventListener('scroll', handleScroll);
                return () => window.removeEventListener('scroll', handleScroll);
            }, []);

            return (
                <nav className={`fixed w-full z-50 transition-all duration-300 border-b border-white/10 ${scrolled ? 'glass-nav shadow-xl py-3' : 'bg-transparent py-6'}`}>
                    <div className="container mx-auto px-6 md:px-12 flex justify-between items-center">
                        <div className="flex items-center gap-3 cursor-pointer" onClick={() => window.scrollTo(0,0)}>
                            <div className="w-10 h-10 bg-accent rounded-lg shadow-lg flex items-center justify-center text-white transform transition hover:scale-105">
                                <i className="fas fa-university text-xl"></i>
                            </div>
                            <div className="font-bold text-2xl tracking-tight leading-none text-white">
                                FUD <span className="text-accent block text-[10px] tracking-[0.2em] uppercase mt-1">OIRMF System</span>
                            </div>
                        </div>
                        <div className="hidden md:flex gap-8 text-sm font-bold text-blue-100">
                            <a href="#features" className="hover:text-white transition">Features</a>
                            <a href="#workflow" className="hover:text-white transition">Workflow</a>
                            <a href="#security" className="hover:text-white transition">Security</a>
                        </div>
                        <div className="flex gap-4">
                            <button onClick={() => setView('login')} className="px-6 py-2.5 bg-accent text-white rounded-full font-bold shadow-[0_0_15px_rgba(46,109,180,0.5)] hover:bg-blue-500 hover:shadow-[0_0_25px_rgba(46,109,180,0.7)] transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
                                <i className="fas fa-lock"></i> Staff Portal
                            </button>
                        </div>
                    </div>
                </nav>
            );
        };

        const HeroCarousel = () => {
            const [current, setCurrent] = useState(0);
            const slides = [
                {
                    title: "Upholding Academic Integrity",
                    subtitle: "The authoritative digital framework for tracking and resolving examination malpractice at the Federal University Dutse.",
                    bg: "from-primary via-[#112a52] to-dark",
                    icon: "fa-shield-alt"
                },
                {
                    title: "Automated Disciplinary Workflow",
                    subtitle: "Seamlessly transition cases from invigilator reports to committee hearings with an immutable audit trail.",
                    bg: "from-dark via-primary to-accent",
                    icon: "fa-project-diagram"
                },
                {
                    title: "Data-Driven Decisions",
                    subtitle: "Empower university administration with real-time analytics, dynamic reporting, and actionable insights.",
                    bg: "from-[#0a192f] via-primary to-[#1e3a8a]",
                    icon: "fa-chart-network"
                }
            ];

            useEffect(() => {
                const timer = setInterval(() => {
                    setCurrent((prev) => (prev + 1) % slides.length);
                }, 6000);
                return () => clearInterval(timer);
            }, []);

            return (
                <header className="relative pt-32 pb-20 md:pt-48 md:pb-40 overflow-hidden bg-dark min-h-[90vh] flex items-center">
                    {/* Dynamic Backgrounds */}
                    {slides.map((slide, index) => (
                        <div 
                            key={index}
                            className={`absolute inset-0 bg-gradient-to-br ${slide.bg} transition-opacity duration-1000 ease-in-out ${index === current ? 'opacity-100' : 'opacity-0'}`}
                        >
                            {/* Abstract Overlays */}
                            <div className="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.04] mix-blend-overlay"></div>
                            <div className="absolute -top-40 -right-40 w-96 h-96 bg-accent rounded-full mix-blend-screen filter blur-[100px] opacity-30 animate-pulse"></div>
                            <div className="absolute -bottom-40 -left-40 w-96 h-96 bg-blue-400 rounded-full mix-blend-screen filter blur-[100px] opacity-20"></div>
                        </div>
                    ))}

                    <div className="container mx-auto px-6 md:px-12 relative z-10">
                        <div className="max-w-4xl mx-auto text-center">
                            <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/20 text-blue-200 text-xs font-bold uppercase tracking-wider mb-8 backdrop-blur-sm animate-slide-up">
                                <span className="w-2 h-2 rounded-full bg-accent animate-pulse"></span>
                                Online Exam Incident Management  Digital Framework for FUD Exam Office
                            </div>
                            
                            <div className="min-h-[200px]">
                                {slides.map((slide, index) => (
                                    <div key={index} className={`transition-all duration-700 absolute left-0 right-0 ${index === current ? 'opacity-100 transform translate-y-0 relative' : 'opacity-0 transform translate-y-8 absolute pointer-events-none'}`}>
                                        <div className="text-accent text-5xl mb-6 opacity-80"><i className={`fas ${slide.icon}`}></i></div>
                                        <h1 className="text-5xl md:text-7xl font-extrabold mb-6 tracking-tight text-white leading-tight drop-shadow-lg">
                                            {slide.title}
                                        </h1>
                                        <p className="text-xl text-blue-100 mb-10 max-w-2xl mx-auto leading-relaxed opacity-90">
                                            {slide.subtitle}
                                        </p>
                                    </div>
                                ))}
                            </div>

                            <div className="mt-8 flex justify-center gap-6 animate-slide-up" style={{animationDelay: '0.4s'}}>
                                <button onClick={() => setView('login')} className="px-8 py-4 bg-white text-primary rounded-full font-extrabold text-lg hover:bg-gray-100 transition-all shadow-[0_0_30px_rgba(255,255,255,0.3)] hover:shadow-[0_0_40px_rgba(255,255,255,0.5)] flex items-center gap-3 transform hover:-translate-y-1">
                                    Access Secure Portal <i className="fas fa-arrow-right"></i>
                                </button>
                            </div>

                            {/* Carousel Indicators */}
                            <div className="flex justify-center gap-3 mt-16">
                                {slides.map((_, idx) => (
                                    <button 
                                        key={idx} 
                                        onClick={() => setCurrent(idx)}
                                        className={`h-1.5 rounded-full transition-all duration-300 ${current === idx ? 'w-8 bg-accent' : 'w-4 bg-white/30 hover:bg-white/50'}`}
                                    />
                                ))}
                            </div>
                        </div>
                    </div>
                </header>
            );
        };

        const FeaturesSection = () => (
            <section id="features" className="py-24 bg-white relative">
                <div className="container mx-auto px-6 md:px-12">
                    <div className="text-center max-w-3xl mx-auto mb-16">
                        <h2 className="text-accent font-bold tracking-widest uppercase text-sm mb-3">Enterprise Capabilities</h2>
                        <h3 className="text-3xl md:text-5xl font-extrabold text-primary mb-6 tracking-tight">Built for Institutional Rigor</h3>
                        <p className="text-gray-500 text-lg">Eliminate paperwork, ensure transparency, and accelerate resolution times with our purpose-built toolset.</p>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                        {[
                            { icon: 'fa-file-signature', title: 'Digital Reporting', desc: 'Invigilators can log incidents instantly with digital evidence attachments directly from the exam hall.' },
                            { icon: 'fa-balance-scale', title: 'Fair Adjudication', desc: 'Automated committee assignments, hearing scheduling, and immutable audit logs ensure absolute fairness.' },
                            { icon: 'fa-chart-pie', title: 'Executive Analytics', desc: 'Deans and Registry staff receive real-time metrics on malpractice trends across all faculties and departments.' }
                        ].map((feat, idx) => (
                            <div key={idx} className="p-8 rounded-2xl bg-gray-50 border border-gray-100 hover:border-accent/30 hover:shadow-2xl hover:shadow-blue-900/5 transition-all duration-300 group">
                                <div className="w-14 h-14 bg-white rounded-xl shadow-sm border border-gray-100 flex items-center justify-center text-2xl text-accent mb-6 group-hover:scale-110 transition-transform duration-300">
                                    <i className={`fas ${feat.icon}`}></i>
                                </div>
                                <h4 className="text-xl font-bold text-gray-900 mb-3">{feat.title}</h4>
                                <p className="text-gray-600 leading-relaxed">{feat.desc}</p>
                            </div>
                        ))}
                    </div>
                </div>
            </section>
        );

        const WorkflowSection = () => (
            <section id="workflow" className="py-24 bg-gray-50 border-t border-gray-200">
                <div className="container mx-auto px-6 md:px-12">
                    <div className="flex flex-col lg:flex-row items-center gap-16">
                        <div className="lg:w-1/2">
                            <h2 className="text-accent font-bold tracking-widest uppercase text-sm mb-3">Standardized Process</h2>
                            <h3 className="text-3xl md:text-5xl font-extrabold text-primary mb-6 tracking-tight">How the Framework Operates</h3>
                            <p className="text-gray-500 text-lg mb-8">A strictly enforced state machine guarantees that every case follows the official university statutes without deviation.</p>
                            
                            <div className="space-y-6">
                                {[
                                    { step: '1', title: 'Incident Triage', text: 'Exam Officers review incoming reports and formally open case files.' },
                                    { step: '2', title: 'Committee Investigation', text: 'Assigned members review evidence and append official notes.' },
                                    { step: '3', title: 'Disciplinary Hearing', text: 'Formal hearings are scheduled, and verdicts are recorded securely.' }
                                ].map((item, i) => (
                                    <div key={i} className="flex gap-4">
                                        <div className="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 text-accent font-black flex items-center justify-center border border-blue-200">{item.step}</div>
                                        <div>
                                            <h5 className="font-bold text-gray-900 text-lg">{item.title}</h5>
                                            <p className="text-gray-600">{item.text}</p>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                        <div className="lg:w-1/2 w-full">
                            {/* Decorative Mockup */}
                            <div className="bg-white p-2 rounded-2xl shadow-2xl border border-gray-200 transform lg:rotate-2 hover:rotate-0 transition duration-500">
                                <div className="bg-gray-100 rounded-xl overflow-hidden border border-gray-200">
                                    <div className="bg-gray-200 px-4 py-2 flex items-center gap-2 border-b border-gray-300">
                                        <div className="w-3 h-3 rounded-full bg-red-400"></div><div className="w-3 h-3 rounded-full bg-yellow-400"></div><div className="w-3 h-3 rounded-full bg-green-400"></div>
                                        <div className="ml-4 text-xs font-mono text-gray-500">case_workflow.php</div>
                                    </div>
                                    <div className="p-6 space-y-4">
                                        <div className="flex justify-between items-center p-3 bg-white rounded border border-gray-200 shadow-sm"><span className="font-bold text-sm text-gray-700">CASE-2024-0012</span><span className="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-1 rounded uppercase">Resolved</span></div>
                                        <div className="flex justify-between items-center p-3 bg-white rounded border border-gray-200 shadow-sm"><span className="font-bold text-sm text-gray-700">CASE-2024-0013</span><span className="bg-yellow-100 text-yellow-700 text-[10px] font-bold px-2 py-1 rounded uppercase">Investigation</span></div>
                                        <div className="flex justify-between items-center p-3 bg-white rounded border border-gray-200 shadow-sm"><span className="font-bold text-sm text-gray-700">CASE-2024-0014</span><span className="bg-orange-100 text-orange-700 text-[10px] font-bold px-2 py-1 rounded uppercase">Hearing</span></div>
                                        <div className="h-2 bg-gray-200 rounded w-1/2 mt-6"></div>
                                        <div className="h-2 bg-gray-200 rounded w-3/4"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        );

        const CTASection = () => (
            <section className="relative py-24 bg-primary overflow-hidden">
                <div className="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.05] mix-blend-overlay"></div>
                <div className="absolute right-0 top-0 w-1/2 h-full bg-gradient-to-l from-accent/40 to-transparent"></div>
                <div className="container mx-auto px-6 md:px-12 relative z-10 text-center">
                    <h2 className="text-4xl md:text-5xl font-extrabold text-white mb-6 tracking-tight">Ready to access the system?</h2>
                    <p className="text-xl text-blue-200 mb-10 max-w-2xl mx-auto">Log in with your official university credentials to view your assigned dashboard and tools.</p>
                    <button onClick={() => setView('login')} className="px-10 py-4 bg-white text-primary rounded-full font-extrabold text-lg hover:bg-gray-100 transition-all shadow-xl hover:shadow-2xl hover:-translate-y-1 inline-flex items-center gap-3">
                        <i className="fas fa-sign-in-alt"></i> Proceed to Login
                    </button>
                </div>
            </section>
        );

        const Footer = () => (
            <footer className="bg-dark text-gray-400 py-12 border-t border-gray-800">
                <div className="container mx-auto px-6 md:px-12 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div className="text-center md:text-left">
                        <span className="text-2xl font-bold text-white tracking-tight">
                            FUD<span className="text-accent">OIRMF</span>
                        </span>
                        <p className="text-sm mt-2 text-gray-500">Federal University Dutse &copy; {new Date().getFullYear()}</p>
                    </div>
                    <div className="flex gap-8 text-sm font-medium">
                        <a href="#" className="hover:text-white transition">Privacy Policy</a>
                        <a href="#" className="hover:text-white transition">IT Support</a>
                        <a href="#" className="hover:text-white transition">University Portal</a>
                    </div>
                    <div className="text-sm text-gray-600 flex items-center gap-2">
                        <i className="fas fa-shield-check"></i> Secured System
                    </div>
                </div>
            </footer>
        );

        const Landing = () => (
            <div className="animate-fade-in">
                <Navbar />
                <HeroCarousel />
                <FeaturesSection />
                <WorkflowSection />
                <CTASection />
                <Footer />
            </div>
        );

        // --- LOGIN COMPONENT ---
        const Login = () => {
            const [form, setForm] = useState({ email: '', password: '' });
            const [err, setErr] = useState('');
            const [loading, setLoading] = useState(false);

            const submit = async (e) => {
                e.preventDefault();
                setLoading(true); setErr('');
                try {
                    const res = await apiCall('login', form);
                    if(res.data.success) redirectUser(res.data.data.role);
                } catch(e) { 
                    setErr(e.response?.data?.message || 'Authentication Failed'); 
                    axios.post(`${API}?action=check_auth`).then(r => setCsrf(r.data.csrf_token));
                } finally {
                    setLoading(false);
                }
            };

            return (
                <div className="min-h-screen flex items-center justify-center p-6 bg-gray-50 relative overflow-hidden animate-fade-in">
                    {/* Decorative Background for Login */}
                    <div className="absolute top-0 left-0 w-full h-64 bg-primary rounded-b-[50%] scale-150 transform -translate-y-24"></div>

                    <div className="w-full max-w-md relative z-10">
                        <div className="text-center mb-8">
                            <div className="w-16 h-16 bg-white text-primary rounded-xl shadow-xl mx-auto flex items-center justify-center mb-6 transform rotate-3">
                                <i className="fas fa-university text-3xl"></i>
                            </div>
                            <h2 className="text-3xl font-extrabold text-white drop-shadow-md">Staff Portal</h2>
                            <p className="text-blue-100 mt-2 font-medium">Authorized personnel access only</p>
                        </div>
                        
                        <form onSubmit={submit} className="bg-white p-8 rounded-2xl shadow-2xl border border-gray-100 relative">
                            <button type="button" onClick={() => setView('landing')} className="absolute top-4 right-4 w-8 h-8 rounded-full bg-gray-50 text-gray-400 hover:text-gray-700 hover:bg-gray-200 flex items-center justify-center transition">
                                <i className="fas fa-times"></i>
                            </button>
                            
                            {err && <div className="bg-red-50 border-l-4 border-red-500 text-red-700 p-3 rounded mb-6 text-sm flex items-start gap-2"><i className="fas fa-exclamation-circle mt-0.5"></i> {err}</div>}
                            
                            <div className="space-y-6">
                                <div>
                                    <label className="block text-sm font-bold text-gray-700 mb-1.5">Official Email</label>
                                    <div className="relative">
                                        <i className="fas fa-envelope absolute left-4 top-3.5 text-gray-400"></i>
                                        <input className="w-full pl-11 pr-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:border-accent focus:bg-white focus:ring-2 focus:ring-accent/20 outline-none transition" type="email" placeholder="staff@fud.edu.ng" onChange={e => setForm({...form, email: e.target.value})} required/>
                                    </div>
                                </div>
                                <div>
                                    <label className="block text-sm font-bold text-gray-700 mb-1.5">Password</label>
                                    <div className="relative">
                                        <i className="fas fa-lock absolute left-4 top-3.5 text-gray-400"></i>
                                        <input className="w-full pl-11 pr-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:border-accent focus:bg-white focus:ring-2 focus:ring-accent/20 outline-none transition" type="password" placeholder="••••••••" onChange={e => setForm({...form, password: e.target.value})} required/>
                                    </div>
                                </div>
                                <button disabled={loading} className="w-full bg-primary text-white p-3.5 rounded-lg font-bold hover:bg-accent transition shadow-lg shadow-blue-900/20 flex items-center justify-center gap-2 mt-2 disabled:opacity-70">
                                    {loading ? <i className="fas fa-circle-notch fa-spin"></i> : <><i className="fas fa-sign-in-alt"></i> Authenticate</>}
                                </button>
                            </div>
                            
                            <div className="mt-6 text-center border-t border-gray-100 pt-6">
                                <p className="text-xs text-gray-500"><i className="fas fa-shield-check text-green-500 mr-1"></i> Protected by FUD IT Security</p>
                            </div>
                        </form>
                    </div>
                </div>
            );
        };

        return view === 'landing' ? <Landing/> : <Login/>;
    }

    const root = ReactDOM.createRoot(document.getElementById('root'));
    root.render(<App />);
</script>
</body>
</html>