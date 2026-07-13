local ThumbnailGenerator = game:GetService("ThumbnailGenerator")
pcall(function() game:GetService("ContentProvider"):SetBaseUrl("http://www.noname.xyz/") end)

print("sniff my finger")

game:GetService("ScriptContext").ScriptsDisabled = true
game:GetService("StarterGui").ShowDevelopmentGui = false
game:Load("http://www.noname.xyz/asset/?id={{assetid}}&AccessKey=u1pZJEnTXzVoMezo1MLE7NMoS14i9ltn")
game:GetService("ScriptContext").ScriptsDisabled = true
game:GetService("StarterGui").ShowDevelopmentGui = false

return ThumbnailGenerator:Click("PNG", 720, 500, --[[hideSky = ]] false) -- fake sky for optimization