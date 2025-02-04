import React, { useState, useEffect } from "react";
import axios from "axios";

const LanguageSelector = () => {
    const [languages, setLanguages] = useState([]);
    const [activeLanguage, setActiveLanguage] = useState(null);
    const [showDropdown, setShowDropdown] = useState(false);

    useEffect(() => {
        axios.get("/api/api_languages")
            .then(response => {
                const languages = response.data.member; // Extraire la liste des langues
                setLanguages(languages);
                if (languages.length > 0) {
                    setActiveLanguage(languages[0]); // Définir la première langue comme active
                }
            })
            .catch(error => {
                console.error("Error fetching languages:", error);
            });
    }, []);

    const handleLanguageChange = (language) => {
        setActiveLanguage(language);
        setShowDropdown(false);
    };

    return (
        <div className="relative inline-block text-left">
            <button
                className="bg-gray-300 px-4 py-2 rounded-md flex items-center gap-2"
                onClick={() => setShowDropdown(!showDropdown)}
            >
                {activeLanguage?.code.toUpperCase()} ▼
            </button>

            {showDropdown && (
                <div className="absolute right-0 mt-2 w-48 bg-white border rounded-md shadow-lg">
                    {languages.map((lang) => (
                        <label
                            key={lang.code}
                            className="flex items-center px-4 py-2 hover:bg-gray-200 cursor-pointer"
                        >
                            <input
                                type="checkbox"
                                checked={activeLanguage?.code === lang.code}
                                onChange={() => handleLanguageChange(lang)}
                                className="mr-2"
                            />
                            {lang.code.toUpperCase()} {/* Afficher uniquement le code */}
                        </label>
                    ))}
                </div>
            )}
        </div>
    );
};

export default LanguageSelector;