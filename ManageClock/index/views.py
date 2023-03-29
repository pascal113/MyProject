from django.shortcuts import render

# Create your views here.

def indexView(request):
    title = '首页'
    classContent = ''

    return render(request, 'index.html', locals())